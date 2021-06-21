<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\DomainException;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Infastructure\Services\EfficiencyAnalysisService;

class EfficiencyAnalysesAssessmentsController extends Controller
{
    private EfficiencyAnalysisRepository $repository;
    private EfficiencyAnalysisService $analysisService;
    private EntityManagerInterface $em;

    public function __construct(EfficiencyAnalysisRepository $repository,
                                EntityManagerInterface $em,
                                EfficiencyAnalysisService $analysisService)
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
