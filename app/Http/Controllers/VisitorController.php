<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        return view('visitors.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name'      => 'required|string|max:100',
            'first_name'     => 'required|string|max:100',
            'middle_initial' => 'nullable|string|max:5',
            'name_extension' => 'nullable|string|max:10',
            'age'            => 'required|integer|min:1|max:120',
            'gender'         => 'required|in:Male,Female,Prefer not to say',
            'barangay'       => 'required|string|max:100',
            'municipality'   => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'contact_number' => 'required|string|max:20',
            'purpose'        => 'required|string|max:100',
            'purpose_other'  => 'nullable|required_if:purpose,Others|string|max:200',
        ]);

        Visitor::create($validated);

        return back()->with('success', 'Log entry recorded successfully!');
    }
}
