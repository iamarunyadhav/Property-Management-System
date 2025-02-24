<?php

namespace App\Services;

use App\Repositories\PropertyRepository;
use Illuminate\Http\Request;

class PropertyService
{
    protected $propertyRepo;

    public function __construct(PropertyRepository $propertyRepo)
    {
        $this->propertyRepo = $propertyRepo;
    }

    public function getAllProperties(Request $request)
    {
        $filters = $request->only(['name', 'rent_min', 'rent_max']);
        return $this->propertyRepo->getAll($filters);
    }

    public function getPropertyById($id)
    {
        return $this->propertyRepo->findById($id);
    }

    public function createProperty(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'rent_amount' => 'required|numeric',
        ]);

        return $this->propertyRepo->create(array_merge($data, ['owner_id' => auth()->id()]));
    }

    public function updateProperty($id, Request $request)
    {
        $property = $this->propertyRepo->findById($id);
        if (!$property || $property->owner_id !== auth()->id()) {
            return null;
        }

        $property->update($request->only(['name', 'address', 'rent_amount']));
        return $property;
    }

    public function deleteProperty($id)
    {
        $property = $this->propertyRepo->findById($id);
        if (!$property) {
            return false;
        }
        return $this->propertyRepo->delete($property);
    }
}
