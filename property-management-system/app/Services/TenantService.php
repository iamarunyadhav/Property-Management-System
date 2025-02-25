<?php

namespace App\Services;

use App\Repositories\TenantRepository;
use App\Repositories\PropertyRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\RentService;

class TenantService
{
    protected $tenantRepository;
    protected $propertyRepository;

    public function __construct(TenantRepository $tenantRepository, PropertyRepository $propertyRepository)
    {
        $this->tenantRepository = $tenantRepository;
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllTenants()
    {
        return $this->tenantRepository->getAllTenants(Auth::id());
    }

    public function getTenantById($id)
    {
        return $this->tenantRepository->findById($id, Auth::id());
    }

    public function createTenant(array $data)
    {
        return $this->tenantRepository->create($data);
    }

    public function updateTenant($id, array $data)
    {
        $tenant = $this->tenantRepository->findById($id, Auth::id());
        if (!$tenant) {
            return null;
        }

        $this->tenantRepository->update($tenant, $data);
        return $tenant;
    }

    public function deleteTenant($id)
    {
        $tenant = $this->tenantRepository->findById($id, Auth::id());
        if (!$tenant) {
            return false;
        }

        return $this->tenantRepository->delete($tenant);
    }

    public function getMonthlyRent($ids)
    {

        $tenants = $this->tenantRepository->findRentById($ids, Auth::id());
        if (!$tenants) {
            return null;
        }
         foreach($tenants as $tenant)
         {
            $property = $tenant->property;
         }
         $data=RentService::calculateRentDistribution($property, $ids);

        $tenants = $data['tenants'];
        $filteredData = [];

        foreach ($tenants as $item) {
            if (in_array($item['id'], $ids)) {
                 $filteredData[] = $item; // Add the matching item to the new array
            }
        }
        // Reset array keys
        $filteredData = array_values($filteredData);
        return $filteredData;
    }
}

?>
