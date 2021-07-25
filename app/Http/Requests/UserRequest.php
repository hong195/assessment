<?php

namespace App\Http\Requests;

use App\DataTransferObjects\UserDto;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends AbstractJsonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'role' => ['required'],
            'login' => ['required', 'string'],
        ];
    }

    public function getDto() : UserDto
    {
        return new UserDto($this->get('first_name'),
            $this->get('last_name'),
            $this->get('middle_name'),
            $this->get('login'),
            $this->get('role'),
            $this->get('password'));
    }
}
