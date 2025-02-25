<?php
namespace App\Repositories;

use App\Models\Property;

class PropertyRepository
{
    public function getAllProperties($filters, $userId)
    {
        $query = Property::with('tenants')->where('owner_id', $userId);

        if (isset($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }

        if (isset($filters['rent_min'])) {
            $query->where('rent_amount', '>=', $filters['rent_min']);
        }

        if (isset($filters['rent_max'])) {
            $query->where('rent_amount', '<=', $filters['rent_max']);
        }

        return $query->paginate(10);
    }

    public function findById($id, $userId)
    {
        return Property::with('tenants')->where('owner_id', $userId)->find($id);
    }

    public function create(array $data)
    {
        return Property::create($data);
    }

    public function update(Property $property, array $data)
    {
        return $property->update($data);
    }

    public function delete(Property $property)
    {
        return $property->delete();
    }

}


?>
