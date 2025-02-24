<?php
namespace App\Services;

class RentService
{
    // public static function calculateRentDistribution($property)
    // {
    //     $totalRent = $property->rent_amount;
    //     $tenants = $property->tenants;

    //     if ($tenants->isEmpty()) {
    //         return null;
    //     }

    //     $defaultShare = round($totalRent / $tenants->count(), 2);
    //     $rentDetails = [];

    //     foreach ($tenants as $tenant) {
    //         $share = $tenant->agreement_percentage
    //                  ? ($tenant->agreement_percentage / 100) * $totalRent
    //                  : $defaultShare;

    //         $rentDetails[] = [
    //             'name' => $tenant->name,
    //             'rent_share' => $share,
    //             //lo logics related to this late_fee therefore always default 0
    //             'late_fee' => 0
    //         ];
    //     }

    //     return [
    //         'total_rent' => $totalRent,
    //         'property_name' => $property->name,
    //         'tenants' => $rentDetails
    //     ];
    // }





    //all possible use case scenario realworld implementation

    public static function calculateRentDistribution($property)
    {
        $totalRent = $property->rent_amount;

        $tenants = $property->tenants;
        if ($tenants->isEmpty()) {
            return [
                'error' => 'No tenants available for rent distribution'
            ];
        }

        // Step 1: Separate tenants with and without agreements
        $tenantsWithAgreement = [];
        $tenantsWithoutAgreement = [];
        $totalAgreementPercentage = 0;

        foreach ($tenants as $tenant) {
            if ($tenant->agreement_percentage) {
                $tenantsWithAgreement[] = $tenant;
                $totalAgreementPercentage += $tenant->agreement_percentage;
            } else {
                $tenantsWithoutAgreement[] = $tenant;
            }
        }

        $rentDetails = [];

        // Case 1: One Tenant - Pays full rent
        if (count($tenants) == 1) {
            $rentDetails[] = [
                'id' => $tenant->id,
                'name' => $tenants[0]->name,
                'rent_share' => $totalRent,
                'late_fee' => 0
            ];
        }
        // Case 2: All tenants have agreement, total = 100%
        elseif ($totalAgreementPercentage == 100) {
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
        // Case 3: Agreements exist, but total < 100%
        elseif ($totalAgreementPercentage > 0 && $totalAgreementPercentage < 100) {
            $assignedRent = 0;

            foreach ($tenantsWithAgreement as $tenant) {
                $share = ($tenant->agreement_percentage / 100) * $totalRent;
                $assignedRent += $share;

                $rentDetails[] = [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'rent_share' => round($share, 2),
                    'late_fee' => 0
                ];
            }

            // Remaining rent
            $remainingRent = $totalRent - $assignedRent;

            if (!empty($tenantsWithoutAgreement)) {
                $equalShare = round($remainingRent / count($tenantsWithoutAgreement), 2);

                foreach ($tenantsWithoutAgreement as $tenant) {
                    $rentDetails[] = [
                        'id' => $tenant->id,
                        'name' => $tenant->name,
                        'rent_share' => $equalShare,
                        'late_fee' => 0
                    ];
                }
            } else {
                // Case where agreement % < 100%, but no tenants left to split the rent
                $rentDetails[] = [
                    'name' => 'Uncovered Rent',
                    'rent_share' => round($remainingRent, 2),
                    'late_fee' => 0
                ];
            }
        }
        // Case 4: No agreements - Split equally
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

        return [
            'total_rent' => $totalRent,
            'property_name' => $property->name,
            'tenants' => $rentDetails
        ];
    }
}

?>
