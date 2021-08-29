<?php

namespace App\Http\Controllers;

use App\Domain\Model\Assessment\Assessment;
use App\Domain\Model\Employee\Employee;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Infrastructure\Services\FinalGradesQuery;
use Illuminate\Http\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PharmaciesCompletedFinalGradeController extends Controller
{
    private FinalGradesQuery $finalGradesQuery;
    private PharmacyRepository $pharmacyRepository;

    public function __construct(FinalGradesQuery   $finalGradesQuery,
                                PharmacyRepository $pharmacyRepository
    )
    {
        $this->finalGradesQuery = $finalGradesQuery;
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function index(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        $finalGrades = $this->finalGradesQuery
            ->byStatus('completed')
            ->byMonth($month)->byYear($year)->execute();
        $employeesIds = array_map(function(FinalGrade $finalGrade) {
            return (string) $finalGrade->getEmployeeId();
        }, $finalGrades);

        if (empty($employeesIds) || empty($finalGrades)) {
            return [];
        }

        $pharmacies = $this->pharmacyRepository->findByEmployeeIds($employeesIds);

        $pharmacies = array_map(function(Pharmacy $pharmacy) use ($finalGrades){
            $employees = array_map(function(Employee $employee) use ($finalGrades){
                $employeeFinalGrade = [
                    'total' => 0,
                    'scored' => 0,
                    'scored_diff' => 1.1,
                    'conversion' => 0,
                    'difference' => 0,
                ];

                foreach ($finalGrades as $finalGrade) {
                    if (!$employee->getId()->isEqual($finalGrade->getEmployeeId())) {
                        continue;
                    }
                    $employeeFinalGrade = [
                        'id' => (string) $finalGrade->getId(),
                        'employee_id' => (string) $finalGrade->getEmployeeId(),
                        'total' => $finalGrade->getTotal(),
                        'scored' => $finalGrade->getScored(),
                        'scored_diff' => $finalGrade->getScored() / $finalGrade->getTotal(),
                        'conversion' => $finalGrade->getTotalSaleConversion(),
                        'month' => (string) $finalGrade->getMonth(),
                        'status' => (string) $finalGrade->getStatus(),
                        'assessments' => array_map(function(Assessment $assessment) {
                            return [
                                'check'  => [
                                    'amount' => $assessment->getCheck()->getAmount(),
                                    'conversion' => $assessment->getCheck()->getSaleConversion(),
                                    'service_date' => $assessment->getCheck()->getServiceDate(),
                                ],
                                'id' => (string) $assessment->getId(),
                                'reviewer' => [
                                    'id' => (string) $assessment->getReviewer()->getReviewerId(),
                                    'name' => (string) $assessment->getReviewer()->getName()
                                ],
                                'reviewer_id' => $assessment->getReviewer(),
                                'criteria' => self::getSerializer()->normalize($assessment->getCriteria(), 'array'),
                            ];
                        }, $finalGrade->getAssessments()->toArray())
                    ];
                }

                return [
                    'id' => (string) $employee->getId(),
                    'name' => (string) $employee->getName(),
                    'final_grade' => $employeeFinalGrade,
                    'scored_diff' => $employeeFinalGrade['scored_diff']
                ];

            }, $pharmacy->getEmployees()->toArray());

            usort($employees, function($a, $b) {
                return $a['scored_diff'] < $b['scored_diff'] ? -1 : 1;
            });

            return [
                'id' => (string) $pharmacy->getId(),
                'number' => (string) $pharmacy->getNumber(),
                'employees' => $employees
            ];
        }, $pharmacies->toArray());

        return response()->json($pharmacies);
    }

    private static function getSerializer(): Serializer
    {
        $encoders = [new JsonEncoder(), new JsonDecode()];
        $normalizers = [new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
