<?php


namespace Domain\Model\Assessment;


use Domain\Model\User\UserId;

interface AssessmentRepository
{
    public function ofId(AssessmentId $assessmentId) : Assessment;

    public function ofMonth(Month $month) : array;

    public function ofUserId(UserId $userId) : Assessment;

    public function add(Assessment $assessment) : Assessment;
}
