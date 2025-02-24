<?php
 namespace App\Http\Resources;

 use Illuminate\Http\Resources\Json\JsonResource;

 class TenantResource extends JsonResource
 {
     public function toArray($request)
     {
         return [
             'id'                 => $this->id,
             'name'               => $this->name,
             'email'              => $this->email,
             'phone_number'       => $this->phone_number,
             'property'           => new PropertyResource($this->whenLoaded('property')),
             'agreement_percentage' => $this->agreement_percentage,
             'created_at'         => $this->created_at->toDateTimeString(),
             'updated_at'         => $this->updated_at->toDateTimeString(),
         ];
     }
 }

?>
