<?php


namespace Domain\Model\Repository;


use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Month;
use Domain\Model\User\UserId;

interface AssessmentRepository
{
    public function ofId(AssessmentId $assessmentId) : Assessment;

    public function ofMonth(Month $month) : array;

    public function ofUserId(UserId $userId) : Assessment;

    public function add(Assessment $assessment) : void;
}
