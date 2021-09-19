<?php

namespace App\Http\Resources;

use App\Domain\Model\SaleManager\SaleManager;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class SaleManagerResource extends JsonResource
{
    #[ArrayShape(['id' => "int", 'name' => "string", 'pharmacies' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"])]
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {

        /** @var SaleManager $this */
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'pharmacies' => PharmacyResource::collection($this->getPharmacies()->toArray())
        ];
    }
}
