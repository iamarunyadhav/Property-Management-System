<?php

namespace App\Repositories;

use App\Models\Tenant;

class TenantRepository
{
    public function getAllTenants($userId)
    {

        return Tenant::with('property')
            ->whereHas('property', function($query) use ($userId) {
                // Filter tenants by property owner
                $query->where('owner_id', $userId);
            })
            ->paginate(10); // paginate for improvements to show the results (improvements)
    }

    public function findById($id, $userId)
    {
        return Tenant::whereHas('property', fn($query) => $query->where('owner_id', $userId))
                     ->where('id', $id)
                     ->first();
    }

    //custom function for find by tenant id's
    public function findRentById($ids, $userId)
    {
        return Tenant::whereHas('property', fn($query) => $query->where('owner_id', $userId))
        ->whereIn('id', $ids)
        ->get();
    }

    public function create(array $data)
    {
        return Tenant::create($data);
    }

    public function update(Tenant $tenant, array $data)
    {
        return $tenant->update($data);
    }

    public function delete(Tenant $tenant)
    {
        return $tenant->delete();
    }
}

?>
