<?php

namespace App\Http\Requests;

use App\DataTransferObjects\SaleManagerPharmaciesDto;
use JetBrains\PhpStorm\ArrayShape;

class SaleManagerPharmaciesRequest extends AbstractJsonRequest
{
    #[ArrayShape(['sale_manager' => "string[]", 'pharmacies_ids' => "string[]"])]
    public function rules(): array
    {
        return [
            'sale_manager' => ['required', 'integer'],
            'pharmacies_ids' => ['required'],
        ];
    }

    public function getDto(): SaleManagerPharmaciesDto
    {
        return new SaleManagerPharmaciesDto(
            $this->get('sale_manager'),
            $this->get('pharmacies_ids')
        );
    }
}
