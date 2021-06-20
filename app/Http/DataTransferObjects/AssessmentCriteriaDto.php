<?php


namespace App\Http\DataTransferObjects;


class AssessmentCriteriaDto
{
    private array $criteria;

    public static function fromArray(array $data): AssessmentCriteriaDto
    {
        $self = new self;

        foreach ($data as $datum) {
            $criterion = [
                'name' => $datum['name'],
                'selected' => $datum['selected'],
                'description' => !empty($datum['description']) ? $datum['description'] : null
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
