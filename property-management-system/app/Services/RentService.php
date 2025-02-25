<?php
namespace App\Services;

class RentService
{

    public static function calculateRentDistribution($property)
    {
        $totalRent = $property->rent_amount;
        $tenants = $property->tenants;

        if ($tenants->isEmpty()) {
            return [
                'error' => 'No tenants available for rent distribution'
            ];
        }

        // Case 1: Only one tenant - Pays full rent
        if (count($tenants) === 1) {
            return [
                'total_rent' => $totalRent,
                'property_name' => $property->name,
                'tenants' => [
                    [
                        'id' => $tenants[0]->id,
                        'name' => $tenants[0]->name,
                        'rent_share' => $totalRent,
                        'late_fee' => 0
                    ]
                ]
            ];
        }

        $tenantsWithAgreement = [];
        $tenantsWithoutAgreement = [];

        foreach ($tenants as $tenant) {
            if (!empty($tenant->agreement_percentage)) {
                $tenantsWithAgreement[] = $tenant;
            } else {
                $tenantsWithoutAgreement[] = $tenant;
            }
        }

        $rentDetails = [];

        // Case 2: If agreement percentages exist for all tenants
        if (!empty($tenantsWithAgreement)) {
            foreach ($tenantsWithAgreement as $tenant) {
                $share = ($tenant->agreement_percentage / 100) * $totalRent;

                $rentDetails[] = [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'rent_share' => round($share, 2),
                    'late_fee' => 0
                ];
            }
        }
        // Case 3: If no agreements, split rent equally
        else {
            $equalShare = round($totalRent / count($tenants), 2);
            foreach ($tenants as $tenant) {
                $rentDetails[] = [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'rent_share' => $equalShare,
                    'late_fee' => 0
                ];
            }
        }
        // skip other possible cases for now , not included in the project scope

        return [
            'total_rent' => $totalRent,
            'property_name' => $property->name,
            'tenants' => $rentDetails
        ];
    }

}

?>
