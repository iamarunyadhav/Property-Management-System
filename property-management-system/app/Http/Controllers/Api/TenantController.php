<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{

    public function index()
    {
        // $tenantDetails=Tenant::with('property')->where('owner_id', auth()->id())->get();
        $tenantDetails = Tenant::with('property')->whereHas('property', function ($query)
         {
            $query->where('owner_id', auth()->id());
         })->get();


        return response()->json($tenantDetails, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:tenants',
            'phone_number' => 'required|string',
            'property_id' => 'required|exists:properties,id',
            'agreement_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $tenant = Tenant::create($validated);
        return response()->json($tenant, 201);
    }

    public function show(string $id)
    {
        $tenant = Tenant::with('property')->where('owner_id', auth()->id())->find($id);

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found or unauthorized'], 404);
        }

        return response()->json($tenant);
    }

    public function update(Request $request, string $id)
    {
        $tenant = Tenant::whereHas('property', function ($query) {
            $query->where('owner_id', auth()->id());
        })->where('id', $id)->first();

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found or unauthorized'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:tenants,email,' . $id,
            'phone_number' => 'sometimes|required|string',
            'property_id' => 'sometimes|required|exists:properties,id',
            'agreement_percentage' => 'sometimes|nullable|numeric|min:0|max:100',
        ]);

        $tenant->update($validated);
        return response()->json(['message' => 'Tenant updated successfully', 'tenant' => $tenant], 200);
    }

    public function destroy(string $id)
    {
        $tenant = Tenant::whereHas('property', function ($query) {
            $query->where('owner_id', auth()->id());
        })->where('id', $id)->first();

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found or unauthorized'], 404);
        }

        $tenant->delete();

        return response()->json(['message' => 'Tenant removed successfully'], 200);
    }

    public function getMonthlyRent(int $id)
    {
        // Fetch tenant by ID with selected fields only
        $tenant = Tenant::with('property')->where('id', $id)->first();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $property = $tenant->property;

        if (!$property) {
            return response()->json(['error' => 'Property not found for this tenant'], 404);
        }

        $totalRent = $property->rent_amount;
        $tenants = $property->tenants;

        if ($tenants->count() === 0) {
            return response()->json(['error' => 'No tenants available for rent distribution'], 400);
        }

        $defaultShare = round($totalRent / $tenants->count(), 2);

        // Calculate the rent share for the specific tenant
        if ($tenant->agreement_percentage) {
            // Rent is calculated based on the agreement percentage
            $monthlyRent = ($tenant->agreement_percentage / 100) * $totalRent;
        } else {
            // Rent is equally divided
            $monthlyRent = $defaultShare;
        }

        return response()->json([
            'tenant_id' => $tenant->id,
            'name' => $tenant->name,
            'monthly_rent' => $monthlyRent
        ], 200);

    }
}

?>
