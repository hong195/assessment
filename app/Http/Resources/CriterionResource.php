<?php

namespace App\Http\Resources;

use App\Domain\Model\Criterion\Criterion;
use App\Domain\Model\Criterion\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class CriterionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Criterion $this */
        return [
            'id' => (string) $this->getId(),
            'name' => $this->getName(),
            'order' => $this->getOrder(),
            'options' => $this->getOptions()->map(function (Option $option) {
                return [
                  'name' => $option->getName(),
                  'value' => $option->getValue()
                ];
            })->toArray(),
        ];
    }
}
