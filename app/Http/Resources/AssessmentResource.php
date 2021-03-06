<?php

namespace App\Http\Resources;

use App\Domain\Model\Assessment\Assessment;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Assessment $this */
        return [
            'id' => (string) $this->getId(),
            'reviewerId' => (string) $this->getReviewer(),
            'check' => [
                'service_date' => $this->getCheck()->getServiceDate(),
                'amount' => $this->getCheck()->getAmount(),
                'conversion' => $this->getCheck()->getSaleConversion(),
            ],
            'criteria' => self::getSerializer()->normalize($this->getCriteria(), 'array'),
        ];
    }

    private static function getSerializer(): Serializer
    {
        $encoders = [new JsonEncoder(), new JsonDecode()];
        $normalizers = [new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
