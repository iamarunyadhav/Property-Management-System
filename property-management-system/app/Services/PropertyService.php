<?php

namespace App\Services;

use App\Repositories\PropertyRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\RentService;

class PropertyService
{
    protected $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties($filters)
    {
        return $this->propertyRepository->getAllProperties($filters, Auth::id());
    }

    public function getPropertyById($id)
    {
        return $this->propertyRepository->findById($id, Auth::id());
    }

    public function createProperty($data)
    {
        return $this->propertyRepository->create(array_merge($data, ['owner_id' => Auth::id()]));
    }

    public function updateProperty($id, $data)
    {
        $property = $this->propertyRepository->findById($id, Auth::id());
        if (!$property) {
            return null;
        }

        $this->propertyRepository->update($property, $data);
        return $property;
    }

    public function deleteProperty($id)
    {
        $property = $this->propertyRepository->findById($id, Auth::id());
        if (!$property) {
            return false;
        }

        return $this->propertyRepository->delete($property);
    }

    public function rentDistribution($id)
    {
        $property = $this->propertyRepository->findById($id, Auth::id());
        // dd($property);
        return RentService::calculateRentDistribution($property, $tenantCollection = null);
    }

    public function getPublicProperties($filters)
    {
        return $this->propertyRepository->getPublicProperties($filters);
    }
}

?>
