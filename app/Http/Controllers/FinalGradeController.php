<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFinalGradeRequest;
use App\Http\Resources\FinalGradeResource;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\DomainException;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Exceptions\InfrastructureException;
use App\Infrastructure\Services\FinalGradeService;

class FinalGradeController extends Controller
{
    private FinalGradeRepository $repository;
    private EntityManagerInterface $em;
    private FinalGradeService $analysisService;

    public function __construct(FinalGradeRepository $repository,
                                EntityManagerInterface $em,
                                FinalGradeService $analysisService)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->analysisService = $analysisService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return FinalGradeResource::collection($this->repository->all()->toArray());
    }

    public function store(CreateFinalGradeRequest $request): \Illuminate\Http\JsonResponse
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
        return FinalGradeResource::make($this->repository->findOrFail($id));
    }
}
