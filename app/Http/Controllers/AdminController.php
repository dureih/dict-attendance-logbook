<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\User;
use App\Exports\VisitorsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // Admin dashboard with records
    public function dashboard(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('last_name',      'like', "%$s%")
                  ->orWhere('first_name',   'like', "%$s%")
                  ->orWhere('barangay',     'like', "%$s%")
                  ->orWhere('municipality', 'like', "%$s%")
                  ->orWhere('province',     'like', "%$s%")
                  ->orWhere('contact_number','like',"%$s%");
            });
        }

        if ($request->filled('gender'))
            $query->where('gender', $request->gender);

        if ($request->filled('municipality'))
            $query->where('municipality', $request->municipality);

        $visitors          = $query->latest()->paginate(20)->withQueryString();
        $totalCount        = Visitor::count();
        $maleCount         = Visitor::where('gender', 'Male')->count();
        $femaleCount       = Visitor::where('gender', 'Female')->count();
        $municipalities    = Visitor::distinct()->pluck('municipality')->sort()->values();
        $municipalityCount = Visitor::distinct('municipality')->count('municipality');

        return view('admin.dashboard', compact(
            'visitors', 'totalCount', 'maleCount',
            'femaleCount', 'municipalities', 'municipalityCount'
        ));
    }

    // Delete a visitor record
    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return back()->with('success', 'Record deleted successfully.');
    }

    // Export to Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new VisitorsExport($request->all()),
            'dict-attendance-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // Export to PDF
    public function exportPdf(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('last_name',       'like', "%$s%")
                  ->orWhere('first_name',    'like', "%$s%")
                  ->orWhere('barangay',      'like', "%$s%")
                  ->orWhere('municipality',  'like', "%$s%")
                  ->orWhere('contact_number','like', "%$s%");
            });
        }
        if ($request->filled('gender'))       $query->where('gender', $request->gender);
        if ($request->filled('municipality')) $query->where('municipality', $request->municipality);

        $visitors = $query->latest()->get();
        $pdf = Pdf::loadView('exports.visitors-pdf', compact('visitors'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('dict-attendance-' . now()->format('Y-m-d') . '.pdf');
    }

    // ── Admin User Management ─────────────────────────────────

    public function users()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'admin',
        ]);

        return back()->with('success', 'Admin account created successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return back()->with('success', 'Admin account deleted.');
    }
}
