<?php

namespace App\Http\Requests;

class CriterionRequest extends AbstractJsonRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'required']
        ];
    }

    public function getDto(): object
    {
        $obj = new class {
            public string $name;
        };

        $obj->name = $this->get('name');

        return $obj;
    }
}
