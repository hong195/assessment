<?php


namespace App\DataTransferObjects;


use JetBrains\PhpStorm\Pure;

class AssessmentCriteriaDto
{
    private array $criteria;

    #[Pure] public static function fromArray(array $data): AssessmentCriteriaDto
    {
        $self = new self;

        foreach ($data as $datum) {
            $criterion = [
                'name' => $datum['name'],
                'selected' => $datum['selected'],
                'description' => !empty($datum['description']) ? $datum['description'] : null,
                'label' => $datum['label'] ?? null,
            ];

            $self->criteria[] = $criterion;
        }

        return $self;
    }

    /**
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
