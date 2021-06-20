<?php

namespace App\Http\Requests;

use App\Http\DataTransferObjects\CriterionOptionDto;

class CriterionOptionRequest extends AbstractJsonRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'value'=>  ['required', 'numeric']
        ];
    }

    public function getDto(): CriterionOptionDto
    {
        return new CriterionOptionDto($this->get('name'), $this->get('value'));
    }
}
