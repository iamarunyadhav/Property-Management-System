<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TenantResource;
use App\Services\TenantService;
use App\Helpers\ApiResponse;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class TenantController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index()
    {
        $tenants = $this->tenantService->getAllTenants();
        return ApiResponse::success('Tenants retrieved successfully.', TenantResource::collection($tenants));
    }

    public function show($id)
    {
        $tenant = $this->tenantService->getTenantById($id);

        if (!$tenant) {
            return ApiResponse::error('Tenant not found.', 404);
        }

       return ApiResponse::success('Tenant retrieved successfully.', new TenantResource($tenant));
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:tenants',
                'phone_number'=> 'required|string',
                'property_id'=> 'required|exists:properties,id',
                'agreement_percentage'=> 'nullable|numeric|min:0|max:100',
            ]);
           } catch (ValidationValidationException $e) {
              return ApiResponse::error($e->validator->errors()->toArray(), 422);
           }

        $property = Property::where('id', $validated['property_id'])
        ->where('owner_id', auth()->id())
        ->first();

        if (!$property) {
            return ApiResponse::error('Unauthorized: You can only assign tenants to your own properties.', 403);
        }
        $tenant = $this->tenantService->createTenant($validated);
        return ApiResponse::success('Tenant created successfully.', new TenantResource($tenant), 201);
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'               => 'sometimes|required|string',
            'email'              => 'sometimes|required|email|unique:tenants,email,' . $id,
            'phone_number'       => 'sometimes|required|string',
            'property_id'        => 'sometimes|required|exists:properties,id',
            'agreement_percentage' => 'sometimes|nullable|numeric|min:0|max:100',
        ]);

        $tenant = $this->tenantService->updateTenant($id, $data);

        if (!$tenant) {
            return ApiResponse::error('Tenant not found or unauthorized.', 404);
        }

        return ApiResponse::success('Tenant updated successfully.', new TenantResource($tenant));
    }


    public function destroy($id)
    {
        $deleted = $this->tenantService->deleteTenant($id);

        if (!$deleted) {
            return ApiResponse::error('Tenant not found or unauthorized.', 404);
        }

        return ApiResponse::success('Tenant deleted successfully.');
    }


    public function getMonthlyRent($id)
    {
        //here the multiple , separated string id's into array
        $tenantIds = strpos($id, ',') !== false ? explode(',', $id) : [$id];

        // Convert string values to integers and remove invalid values
        $tenantIds = array_filter(array_map('intval', $tenantIds));

        if (empty($tenantIds)) {
            return ApiResponse::error('Invalid tenant IDs provided.', 400);
        }
        $data = $this->tenantService->getMonthlyRent($tenantIds);

        if (!$data) {
            return ApiResponse::error('Tenant not found or unauthorized.', 404);
        }

        return ApiResponse::success('Tenant monthly rent retrieved successfully.', $data);
    }

}


?>
