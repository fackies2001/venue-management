<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function store(Request $request)
    {
        // Dito natin sinisiguro na kasama ang building_id at capacity pag nag-Add
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'capacity' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Venue::create($validated);

        return back()->with('success', 'Venue added successfully!');
    }

    public function update(Request $request, Venue $venue)
    {
        // Dito natin sinisiguro na kasama ang building_id at capacity pag nag-Edit
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'capacity' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $venue->update($validated);

        return back()->with('success', 'Venue updated successfully!');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();
        return back()->with('success', 'Venue deleted successfully!');
    }
}
