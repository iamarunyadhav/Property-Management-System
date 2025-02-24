<?php

namespace App\Repositories;

use App\Models\Property;

class PropertyRepository
{
    public function getAll($filters = [])
    {
        $query = Property::with('tenants')->where('owner_id', auth()->id());

        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }
        if (!empty($filters['rent_min'])) {
            $query->where('rent_amount', '>=', $filters['rent_min']);
        }
        if (!empty($filters['rent_max'])) {
            $query->where('rent_amount', '<=', $filters['rent_max']);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return Property::with('tenants')->where('owner_id', auth()->id())->find($id);
    }

    public function create(array $data)
    {
        return Property::create($data);
    }

    public function update(Property $property, array $data)
    {
        $property->update($data);
        return $property;
    }

    public function delete(Property $property)
    {
        return $property->delete();
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
