<?php

namespace App\Http\Requests;

use App\DataTransferObjects\PharmacyDto;

class PharmacyRequest extends AbstractJsonRequest
{
    public function rules(): array
    {
        return [
            'email' => ['string', 'required'],
            'number' => ['string', 'required']
        ];
    }

    public function getDto(): PharmacyDto
    {
        return new PharmacyDto($this->get('number'), $this->get('email'));
    }
}
