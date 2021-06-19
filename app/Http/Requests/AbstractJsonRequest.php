<?php


namespace App\Http\Requests;


use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractJsonRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    abstract public function rules();

    abstract public function getDto();

    final protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json($validator->errors(), 422)
        );
    }
}
