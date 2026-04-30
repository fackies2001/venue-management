<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::orderBy('name')->get();
        return view('super-admin.divisions.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name'
        ]);

        Division::create([
            'name' => strtoupper(trim($request->name))
        ]);

        return back()->with('success', 'Division added successfully.');
    }

    public function destroy(Division $division)
    {
        $division->delete();
        return back()->with('success', 'Division removed.');
    }
}
