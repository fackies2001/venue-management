<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::withCount('venues')->get();
        $venues = \App\Models\Venue::with('building')->get();

        // Iisang view na lang para sa management ng dalawa
        return view('super-admin.facilities.index', compact('buildings', 'venues'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:buildings,name']);
        Building::create(['name' => $request->name, 'is_active' => $request->has('is_active')]);
        return back()->with('success', 'Building added successfully!');
    }

    public function update(Request $request, Building $building)
    {
        $request->validate(['name' => 'required|string|max:255|unique:buildings,name,' . $building->id]);
        $building->update(['name' => $request->name, 'is_active' => $request->has('is_active')]);
        return back()->with('success', 'Building updated successfully!');
    }

    public function destroy(Building $building)
    {
        $building->delete();
        return back()->with('success', 'Building deleted successfully!');
    }
}
