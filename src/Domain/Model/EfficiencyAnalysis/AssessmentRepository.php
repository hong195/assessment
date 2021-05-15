<?php


namespace Domain\Model\EfficiencyAnalysis;


use Domain\Model\User\UserId;

interface AssessmentRepository
{
    public function ofId(RatingId $assessmentId) : EfficiencyAnalysis;

    public function ofMonth(Month $month) : array;

    public function ofUserId(UserId $userId) : EfficiencyAnalysis;

    public function add(EfficiencyAnalysis $assessment) : EfficiencyAnalysis;
}
