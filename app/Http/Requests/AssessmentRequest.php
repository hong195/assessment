<?php

namespace App\Http\Requests;

use App\DataTransferObjects\AssessmentCriteriaDto;
use App\DataTransferObjects\AssessmentDto;
use App\DataTransferObjects\CheckDto;

class AssessmentRequest extends AbstractJsonRequest
{
    public function rules()
    {
        return [
            'service_date' => ['date', 'required'],
            'amount' => ['integer', 'nullable'],
            'conversion' => ['numeric', 'nullable'],
            'criteria' => ['required'],
            'criteria.*.name' => ['string', 'required'],
            'criteria.*.selected' => ['string', 'required'],
            'criteria.*.description' => ['string', 'nullable'],
            'criteria.*.label' => ['string', 'nullable'],
        ];
    }

    public function getDto(): AssessmentDto
    {
        $check = new CheckDto(
                new \DateTime($this->get('service_date')),
                $this->get('amount') ?? 0,
                $this->get('conversion') ?? 0);

        $criteria = AssessmentCriteriaDto::fromArray($this->get('criteria'));

        return new AssessmentDto($check, $criteria);
    }
}
