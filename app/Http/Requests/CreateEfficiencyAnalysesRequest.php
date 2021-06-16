<?php

namespace App\Http\Requests;

use App\Http\DataTransferObjects\EfficiencyAnalysesDTO;

class CreateEfficiencyAnalysesRequest extends AbstractJsonRequest
{
    public function rules(): array
    {
        return [
            'id' => ['string', 'required'],
            'employee_id' => ['string', 'required'],
            'month' => ['date', 'required'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function getDto() : EfficiencyAnalysesDTO
    {
        return new EfficiencyAnalysesDTO($this->get('id'),
            $this->get('employee_id'), new \DateTime($this->get('month')));
    }
}
