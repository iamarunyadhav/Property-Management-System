<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PropertyController extends Controller
{

    public function index(Request $request)
    {
        // return response(Property::with('tenants')->get(),200);

        $property = Property::with('tenants')->where('owner_id', auth()->id())->get();

          //filter the property with their name
            if ($request->has('name')) {
                $property->where('name', 'LIKE', "%{$request->name}%");
            }

            //filter the property with their rent amount range minimum
            if ($request->has('rent_min')) {
                $property->where('rent_amount', '>=', $request->rent_min);
            }

            //filter the property with their amount range maximum
            if ($request->has('rent_max')) {
                $property->where('rent_amount', '<=', $request->rent_max);
            }

            return response()->json($property, 200);
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


   //rent distribution calculations
    public function rentDistribution($id) {
        $property = Property::with('tenants')->find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        $totalRent = $property->rent_amount;
        $tenants = $property->tenants;
        $property_name=$property->name;

        if ($tenants->count() === 0) {
            return response()->json(['error' => 'No tenants available for rent distribution'], 400);
        }

        $defaultShare = round($totalRent / $tenants->count(), 2);

        $rentDetails = [];

        foreach ($tenants as $tenant) {
            if ($tenant->agreement_percentage) {
                // Rent is calculated based on the agreement percentage
                $share = ($tenant->agreement_percentage / 100) * $totalRent;
            } else {
                // Rent is equally divided
                $share = $defaultShare;
            }

            $rentDetails[] = [
                'name' => $tenant->name,
                'rent_share' => $share,
                //no logics given for late_fee default value
                'late_fee' => 0
            ];
        }
        return response()->json(['total_rent' => $totalRent,'property_name' => $property_name, 'tenants' => $rentDetails]);
    }


    //if needed public listing and advertsising

    public function publicListing(Request $request)
    {

        $property = Property::select(['id', 'name', 'address', 'rent_amount'])->get();

            if ($request->has('name')) {
                $property->where('name', 'LIKE', "%{$request->name}%");
            }

            if ($request->has('rent_min')) {
                $property->where('rent_amount', '>=', $request->rent_min);
            }

            if ($request->has('rent_max')) {
                $property->where('rent_amount', '<=', $request->rent_max);
            }

            return response()->json($property, 200);
    }
}
