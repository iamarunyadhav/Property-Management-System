<?php

namespace App\Repositories;

use App\Models\Property;
use App\Models\Tenant;

class TenantRepository
{
    public function getAllTenants()
    {
        return Tenant::with('property')->whereHas('property', function ($query) {
            $query->where('owner_id', auth()->id());
        })->get();
    }

    public function findTenant($id)
    {
        return Tenant::with('property')->whereHas('property', function ($query) {
            $query->where('owner_id', auth()->id());
        })->find($id);
    }

    public function createTenant(array $data)
    {
        return Tenant::create($data);
    }

    public function updateTenant(Tenant $tenant, array $data)
    {
        $tenant->update($data);
        return $tenant;
    }

    public function deleteTenant(Tenant $tenant)
    {
        return $tenant->delete();
    }

    public function rentDistribution($propertyId)
    {
        $property = Property::with('tenants')->find($propertyId);

        if (!$property) {
            return ['error' => 'Property not found', 'status' => 404];
        }

        $totalRent = $property->rent_amount;
        $tenants = $property->tenants;
        $propertyName = $property->name;

        if ($tenants->count() === 0) {
            return ['error' => 'No tenants available for rent distribution', 'status' => 400];
        }

        $defaultShare = round($totalRent / $tenants->count(), 2);
        $rentDetails = [];

        foreach ($tenants as $tenant) {
            $share = $tenant->agreement_percentage
                ? ($tenant->agreement_percentage / 100) * $totalRent
                : $defaultShare;

            $rentDetails[] = [
                'name' => $tenant->name,
                'rent_share' => round($share, 2),
                'late_fee' => 0, // Default value, can be modified based on additional logic
            ];
        }

        return [
            'total_rent' => $totalRent,
            'property_name' => $propertyName,
            'tenants' => $rentDetails
        ];
    }
}
