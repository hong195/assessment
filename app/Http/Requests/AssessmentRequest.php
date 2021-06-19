<?php

namespace App\Http\Requests;

use App\Http\DataTransferObjects\AssessmentCriteriaDto;
use App\Http\DataTransferObjects\AssessmentDto;
use App\Http\DataTransferObjects\CheckDto;

class AssessmentRequest extends AbstractJsonRequest
{
    public function rules()
    {
        return [
            'service_date' => ['date', 'required'],
            'amount' => ['integer', 'nullable'],
            'conversion' => ['numeric', 'nullable'],
            'criteria.*.name' => ['string', 'required'],
            'criteria.*.selected' => ['numeric', 'required'],
            'criteria.*.description' => ['string', 'nullable'],
        ];
    }

    public function getDto(): AssessmentDto
    {
        $check = new CheckDto(
                $this->get(new \DateTime('service_date')),
                $this->get('amount') ?? 0,
                $this->get('conversion') ?? 0);

        $criteria = AssessmentCriteriaDto::fromArray($this->get('criteria'));

        return new AssessmentDto($check, $criteria);
    }
}
