<?php

namespace App\Http\Requests;

use App\DataTransferObjects\FinalGradeDto;

class CreateFinalGradeRequest extends AbstractJsonRequest
{
    public function rules(): array
    {
        return [
            'employee_id' => ['string', 'required'],
            'assessment_month' => ['date', 'required'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function getDto() : FinalGradeDto
    {
        return new FinalGradeDto($this->get('employee_id'), new \DateTime($this->get('assessment_month')));
    }
}
