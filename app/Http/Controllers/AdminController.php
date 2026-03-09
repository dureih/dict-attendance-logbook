<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Exports\VisitorsExport;
use Illuminate\Http\Request;
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
                $q->where('last_name',       'like', "%$s%")
                  ->orWhere('first_name',    'like', "%$s%")
                  ->orWhere('barangay',      'like', "%$s%")
                  ->orWhere('municipality',  'like', "%$s%")
                  ->orWhere('province',      'like', "%$s%")
                  ->orWhere('contact_number','like', "%$s%");
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

    // Export ALL (filtered) to Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new VisitorsExport($request->all()),
            'dict-attendance-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // Export ALL (filtered) to PDF
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

    // ── SINGLE VISITOR EXPORTS ────────────────────────────────

    // Export single visitor to Excel
    public function exportVisitorExcel(Visitor $visitor)
    {
        return Excel::download(
            new VisitorsExport([], collect([$visitor])),
            'visitor-' . $visitor->id . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // Export single visitor to PDF
    public function exportVisitorPdf(Visitor $visitor)
    {
        $visitors = collect([$visitor]);
        $pdf = Pdf::loadView('exports.visitors-pdf', compact('visitors'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('visitor-' . $visitor->id . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
