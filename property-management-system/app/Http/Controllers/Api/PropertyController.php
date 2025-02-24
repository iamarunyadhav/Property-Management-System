<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;
use App\Helpers\ApiResponse;
use App\Http\Resources\PublicListResource;
use App\Http\Resources\PublicResource;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        $properties = $this->propertyService->getAllProperties($request->all());
        return ApiResponse::success('Properties retrieved successfully.', PropertyResource::collection($properties));
    }

    public function show($id)
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            return ApiResponse::error('Property not found.', 404);
        }

        return ApiResponse::success('Property retrieved successfully.', new PropertyResource($property));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'address'     => 'required|string',
            'rent_amount' => 'required|numeric|min:0',
        ]);

        $property = $this->propertyService->createProperty($data);

        return ApiResponse::success('Property created successfully.', new PropertyResource($property), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'address'     => 'sometimes|string|max:500',
            'rent_amount' => 'sometimes|numeric|min:0',
        ]);

        $property = $this->propertyService->updateProperty($id, $data);

        if (!$property) {
            return ApiResponse::error('Property not found or unauthorized.', 404);
        }

        return ApiResponse::success('Property updated successfully.', new PropertyResource($property));
    }

    public function destroy($id)
    {
        $deleted = $this->propertyService->deleteProperty($id);

        if (!$deleted) {
            return ApiResponse::error('Property not found or unauthorized.', 404);
        }

        return ApiResponse::success('Property deleted successfully.');
    }

    public function rentDistribution($id)
    {
        $data = $this->propertyService->rentDistribution($id);

        if (!$data) {
            return ApiResponse::error('No tenants available for rent distribution.', 400);
        }

        return ApiResponse::success('Rent distribution calculated.', $data);
    }

    public function publicListing(Request $request)
    {
        $properties = $this->propertyService->getPublicProperties($request->all());
        return ApiResponse::success('Public properties retrieved successfully.', PublicListResource::collection($properties));
    }
}


?>
