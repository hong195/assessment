<?php


namespace App\Infrastructure\Services;


use App\Domain\Model\FinalGrade\Status;
use App\Domain\Model\Pharmacy\PharmacyRepository;


class PharmacyFinalGradeService
{
    private FinalGradesQuery $finalGradesQuery;
    private PharmacyRepository $pharmacyRepository;

    public function __construct(FinalGradesQuery $finalGradesQuery, PharmacyRepository $pharmacyRepository)
    {
        $this->finalGradesQuery = $finalGradesQuery;
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function getCompletedYearlyPharmacyFinalGrades(string $pharmacyId = null): array
    {
        $yearlyPharmacyGrades = [];

        if ($pharmacyId) {
            $this->finalGradesQuery->byPharmacies($pharmacyId);
        }

        foreach (range(1,12) as $month) {
            $finalGradeForMonth = $this->finalGradesQuery
                ->byMonth($month)
                ->byStatus(Status::COMPLETED)
                ->execute();


            $yearlyPharmacyGrades[$month] = array_shift($finalGradeForMonth);
        }


        return $yearlyPharmacyGrades;
    }
}
