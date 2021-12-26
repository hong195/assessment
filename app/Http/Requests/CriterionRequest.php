<?php

namespace App\Http\Requests;

use JetBrains\PhpStorm\ArrayShape;

class CriterionRequest extends AbstractJsonRequest
{
    #[ArrayShape(['name' => "string[]", 'order' => "string[]", 'label' => "string[]"])]
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'order' => ['integer', 'nullable'],
            'label' => ['string', 'string'],
        ];
    }

    public function getDto(): object
    {
        $obj = new class {
            public string $name;
            public int $order;
            public string $label;
        };

        $obj->name = $this->get('name');
        $obj->order = (int) $this->get('order') ?? 0;
        $obj->label = $this->get('label') ?? null;

        return $obj;
    }
}
