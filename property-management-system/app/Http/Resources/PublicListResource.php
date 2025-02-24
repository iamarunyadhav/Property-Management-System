<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'address'     => $this->address,
            'rent_amount' => $this->rent_amount
        ];
    }
}

?>
