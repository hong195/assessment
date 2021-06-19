<?php


namespace App\Http\DataTransferObjects;


class AssessmentDto
{
    private CheckDto $checkDto;
    private AssessmentCriteriaDto $criteriaDto;

    /**
     * AssessmentDto constructor.
     * @param CheckDto $dto
     * @param AssessmentCriteriaDto $criteriaDto
     */
    public function __construct(CheckDto $dto, AssessmentCriteriaDto $criteriaDto)
    {
        $this->checkDto = $dto;
        $this->criteriaDto = $criteriaDto;
    }

    /**
     * @return CheckDto
     */
    public function getCheckDto(): CheckDto
    {
        return $this->checkDto;
    }

    /**
     * @return AssessmentCriteriaDto
     */
    public function getCriteriaDto(): AssessmentCriteriaDto
    {
        return $this->criteriaDto;
    }
}
