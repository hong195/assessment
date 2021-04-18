<?php


namespace Domain\Model\Rating;


use Domain\Model\User\UserId;

interface AssessmentRepository
{
    public function ofId(RatingId $assessmentId) : Rating;

    public function ofMonth(Month $month) : array;

    public function ofUserId(UserId $userId) : Rating;

    public function add(Rating $assessment) : Rating;
}
