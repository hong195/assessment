<?php

namespace App\Http\Resources;

use App\Domain\Model\Assessment\Assessment;
use App\Domain\Model\FinalGrade\FinalGrade;
use Illuminate\Http\Resources\Json\JsonResource;

class EfficiencyAnalysesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var FinalGrade $this */
        return [
            'id' => (string) $this->getId(),
            'employeeId' => (string) $this->getEmployeeId(),
            'scored' => $this->getScored(),
            'total' => (string) $this->getTotal(),
            'month' => (string) $this->getMonth(),
            'status' => (string) $this->getStatus(),
            'assessments' => AssessmentResource::collection($this->getAssessments()->toArray())
        ];
    }
}
