<?php

namespace App\Services;

use App\Repositories\TenantRepository;
use Illuminate\Http\Request;

class TenantService
{
    protected $tenantRepo;

    public function __construct(TenantRepository $tenantRepo)
    {
        $this->tenantRepo = $tenantRepo;
    }

    public function getAllTenants()
    {
        return $this->tenantRepo->getAllTenants();
    }

    public function createTenant(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:tenants',
            'phone_number' => 'required|string',
            'property_id' => 'required|exists:properties,id',
            'agreement_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        return $this->tenantRepo->createTenant($data);
    }
}
?>
