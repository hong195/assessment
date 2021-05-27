<?php


namespace Domain\Model\Assessment;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AssessmentCriteriaType extends JsonType
{
    const ASSESSMENT_CRITERIA_TYPE = 'assessment_criteria';

    public function getName()
    {
        return self::ASSESSMENT_CRITERIA_TYPE;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $this->deserialize($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value ? $this->serialize($value) : null;
    }

    public function serialize($data): string
    {
        return $this->getSerializer()->serialize($data, 'json');
    }

    public function deserialize($data)
    {
        return $this->getSerializer()->deserialize($data, Criterion::class, 'json');
    }

    private function getSerializer(): Serializer
    {
        $encoders = [new JsonEncoder(), new JsonDecode()];
        $normalizers = [new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
