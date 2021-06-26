<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEfficiencyAnalysesRequest;
use App\Http\Requests\UpdateEfficiencyAnalysesRequest;
use App\Http\Resources\EfficiencyAnalysesResource;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\DomainException;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Exceptions\InfrastructureException;
use App\Infrastructure\Services\EfficiencyAnalysisService;

class EfficiencyAnalysesController extends Controller
{
    private FinalGradeRepository $repository;
    private EntityManagerInterface $em;
    private EfficiencyAnalysisService $analysisService;

    public function __construct(FinalGradeRepository $repository,
                                EntityManagerInterface $em,
                                EfficiencyAnalysisService $analysisService)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->analysisService = $analysisService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return EfficiencyAnalysesResource::collection($this->repository->all()->toArray());
    }

    public function store(CreateEfficiencyAnalysesRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $dto = $request->getDto();
            $this->analysisService->create($dto->getEmployeeId(), $dto->getMonth());
            $this->em->flush();

            return response()->json(['message' => 'Created!']);

        }catch (DomainException|InfrastructureException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function show($id)
    {
        return EfficiencyAnalysesResource::make($this->repository->findOrFail($id));
    }
}
