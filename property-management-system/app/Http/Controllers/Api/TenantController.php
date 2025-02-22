<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tenant::all();
        $tenantDetails=Tenant::with('property')->get();
        return response()->json($tenantDetails, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the tenant details.
     */
    public function show(string $tenant_id)
    {
        $tenant = Tenant::find($tenant_id);

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        return response()->json($tenant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $tenant_id)
    {
        $tenant = Tenant::find($tenant_id);

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:tenants,email,' . $tenant_id,
            'phone_number' => 'sometimes|required|string',
            'property_id' => 'sometimes|required|exists:properties,id',
            'agreement_percentage' => 'sometimes|nullable|numeric|min:0|max:100',
        ]);

        $tenant->update($validated);
        return response()->json(['message' => 'Tenant updated successfully', 'tenant' => $tenant], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $tenant_id)
    {
        $tenant = Tenant::find($tenant_id);

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $tenant->delete();
        return response()->json(['message' => 'Tenant removed successfully'], 200);
    }
}
