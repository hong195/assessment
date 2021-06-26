<?php

namespace App\Http\Requests;

use App\DataTransferObjects\EmployeeDto;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends AbstractJsonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'pharmacy_id' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
        ];
    }

    public function getDto(): EmployeeDto
    {
        return new EmployeeDto($this->get('pharmacy_id'),
            $this->get('first_name'),
            $this->get('last_name'),
            $this->get('middle_name'),
            new \DateTime($this->get('birthdate')),
            $this->get('gender'));
    }
}
