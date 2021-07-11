<?php

namespace App\Http\Controllers;

use App\Domain\Model\Assessment\Assessment;
use App\Domain\Model\Assessment\AssessmentId;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Http\Requests\AssessmentRequest;
use App\Http\Resources\AssessmentResource;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\DomainException;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Infrastructure\Services\FinalGradeService;

class FinalGradeAssessmentsController extends Controller
{
    private FinalGradeRepository $repository;
    private FinalGradeService $analysisService;
    private EntityManagerInterface $em;

    public function __construct(FinalGradeRepository $repository,
                                EntityManagerInterface $em,
                                FinalGradeService $analysisService)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->analysisService = $analysisService;
    }

    public function store(AssessmentRequest $request, string $efficiencyAnalysesId): \Illuminate\Http\JsonResponse
    {
        try {
            $assessmentDto = $request->getDto();
            $this->analysisService->addAssessment($efficiencyAnalysesId, $assessmentDto);

            return response()->json([
                'message' => 'Created'
            ]);

        } catch (NotFoundEntityException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show(string $id, string $assessmentId)
    {
        /** @var FinalGrade $finalGrade */
        $assessment = $this->analysisService->getAssessment($id, $assessmentId);
        return AssessmentResource::make($assessment);
    }

    public function update(AssessmentRequest $request, string $id, string $assessmentId): \Illuminate\Http\JsonResponse
    {
        try {
            $assessmentDto = $request->getDto();
            $this->analysisService->editAssessment($id,$assessmentId, $assessmentDto);

            return response()->json([
                'message' => 'Created'
            ]);

        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy(string $efficiencyAnalysesId, string $assessmentId): \Illuminate\Http\JsonResponse
    {
        try {
            $this->analysisService->removeAssessment($efficiencyAnalysesId, $assessmentId);

            return response()->json([
                'message' => 'Deleted'
            ]);
        } catch (DomainException $e) {
            return response()->json([
               'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
