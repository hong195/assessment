<?php

namespace App\Http\Controllers;

use App\Domain\Model\Assessment\Assessment;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Infrastructure\Services\FinalGradesQuery;
use Illuminate\Http\Request;

class AssessmentAnalyticsController extends Controller
{
    private FinalGradesQuery $finalGradesQuery;

    public function __construct(FinalGradesQuery $finalGradesQuery)
    {
        $this->finalGradesQuery = $finalGradesQuery;
    }

    public function index(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $data = [];

        $finalGrades = $this->finalGradesQuery->byYear($year)->byMonth($month)->execute();

        /** @var Assessment[] $assessments */
        $assessmentCriteria = array_map(function(FinalGrade $finalGrade) {
            return array_values(array_map(function(Assessment $assessment) {
                return $assessment->getCriteria()->toArray();
            }, $finalGrade->getAssessments()->toArray()));
        }, $finalGrades);

        foreach ($assessmentCriteria as $criteria) {
            foreach ($criteria as $criterion) {
                foreach ($criterion as $item) {
                    if (empty($data[$item->getLabel()])) {
                        $data[$item->getLabel()] = 0;
                    }

                    $maxValue = max(array_map(function($option) {
                        return $option->getValue();
                    },$item->getOptions()->toArray()));

                    foreach ($item->getOptions() as $option) {
                        if ($option->getName() === $item->getSelected()
                                && $maxValue === $option->getValue()) {
                            $data[$item->getLabel()]++;
                        }
                    }
                }
            }
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
