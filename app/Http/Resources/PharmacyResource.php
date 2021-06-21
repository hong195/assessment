<?php

namespace App\Http\Resources;

use Domain\Model\Pharmacy\Pharmacy;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        /** @var Pharmacy $this */
        return [
            'id' => (string) $this->getId(),
            'number' => (string) $this->getNumber(),
            'email' => (string) $this->getEmail(),
            'employee' => []
        ];
    }
}
