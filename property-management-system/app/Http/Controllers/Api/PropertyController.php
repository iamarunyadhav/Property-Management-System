<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{

    public function index()
    {
        $property=Property::with('tenants')->get();
        dd($property);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'rent_amount' => 'required|numeric',
        ]);

        return Property::create(array_merge($validated, ['owner_id' => auth()->id()]));
    }

    public function show(string $id)
    {
        //eager loading
        $property = Property::with('tenants')->find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        return response()->json($property);
    }

    public function update(Request $request, string $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        if ($property->owner_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $property->update($request->only(['name', 'address', 'rent_amount']));
        return response()->json(['message' => 'Property updated successfully']);
    }

    public function destroy(string $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        $property->delete();
        return response()->json(['message' => 'Property deleted successfully']);
    }
}
