<?php

namespace App\Http\Resources;

use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class CriterionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Criterion $this */
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'options' => $this->getOptions()->map(function (Option $option) {
                return [
                  'name' => $option->getName(),
                  'value' => $option->getValue()
                ];
            }),
        ];
    }
}
