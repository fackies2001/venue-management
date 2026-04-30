<?php

namespace App\Http\Controllers\Admin; // Tanggalin ang '\Admin' kung nasa labas lang ito ng Admin folder

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    /**
     * Store a newly created venue in storage.
     */
    public function store(Request $request)
    {
        // ✅ FIXED: Kasama na ang 'room_floor' sa validation
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name'        => 'required|string|max:255',
            'room_floor'  => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:7',
            'capacity'    => 'nullable|integer|min:1',
        ]);

        // Default to active when creating a new venue
        $validated['is_active'] = $request->has('is_active') ? true : true;

        Venue::create($validated);

        return redirect()->back()->with('success', 'Venue added successfully!');
    }

    /**
     * Update the specified venue in storage.
     */
    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);

        // ✅ FIXED: Kasama na rin ang 'room_floor' sa pag-update
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name'        => 'required|string|max:255',
            'room_floor'  => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:7',
            'capacity'    => 'nullable|integer|min:1',
        ]);

        // Kunin ang is_active checkbox value (kung walang pinadala, false)
        $validated['is_active'] = $request->has('is_active');

        $venue->update($validated);

        return redirect()->back()->with('success', 'Venue updated successfully!');
    }

    /**
     * Remove the specified venue from storage.
     */
    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return redirect()->back()->with('success', 'Venue deleted successfully!');
    }
}
