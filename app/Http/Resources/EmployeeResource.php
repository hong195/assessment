<?php

namespace App\Http\Resources;

use App\Domain\Model\Employee\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Employee $this */
        return [
            'id' => (string) $this->getId(),
            'pharmacy_id' => (string) $this->getPharmacy()->getId(),
            'first_name' => $this->getName()->firstName(),
            'last_name' => $this->getName()->lastName(),
            'middle_name' => $this->getName()->middle(),
            'gender' => $this->getGender()->getValue(),
            'birthdate' => $this->getBirthdate()->format('Y-m-d')
        ];
    }
}
