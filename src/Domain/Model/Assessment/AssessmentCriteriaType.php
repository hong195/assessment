<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
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

    public function deserialize($collection): ArrayCollection
    {
        $collection = new ArrayCollection(json_decode($collection));

        return $collection->map(function ($criterion) {

            $options = array_map(function ($option) {
                return new Option($option->name, $option->value);
            }, (array) $criterion->options);

            return new Criterion($criterion->name, $options, $criterion->selectedValue, $criterion->description);
        });
    }

    private function getSerializer(): Serializer
    {
        $encoders = [new JsonEncoder(), new JsonDecode()];
        $normalizers = [new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
