<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriterionRequest;
use App\Http\Resources\CriterionResource;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\DomainException;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\Criterion\CriterionRepository;
use App\Exceptions\NotUniqueCriterionNameException;
use App\Infrastructure\Services\CriterionService;

class CriteriaController extends Controller
{
    private CriterionRepository $criterionRepository;
    private EntityManagerInterface $em;
    private CriterionService $criterionService;

    public function __construct(CriterionRepository $criterionRepository,
                                CriterionService $criterionService,
                                EntityManagerInterface $em)
    {
        $this->criterionRepository = $criterionRepository;
        $this->em = $em;
        $this->criterionService = $criterionService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CriterionResource::collection($this->criterionRepository->all()->toArray());
    }


    public function store(CriterionRequest $request): \Illuminate\Http\JsonResponse
    {
        $dto = $request->getDto();

        try {
            $this->criterionService->create($dto->name, $dto->order);
            $this->em->flush();

            return response()->json([
                'message' => 'Created!'
            ]);

        } catch (NotUniqueCriterionNameException $e) {
            return response()->json([
                'message' => 'Error creating'
            ], $e->getCode());
        }
    }

    public function update(CriterionRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $dto = $request->getDto();

        try {
            $this->criterionService->update($id, $dto->name, $dto->order, $dto->label);
            $this->em->flush();

            return response()->json([
                'message' => 'Updated!'
            ]);

        } catch (DomainException $e) {
            return response()->json([
                'message' => 'Error Updating'
            ], $e->getCode());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->criterionService->removeCriterion($id);
            $this->em->flush();

            return response()->json([
                'message' => 'Deleted'
            ]);

        }catch (NotFoundEntityException $e) {
            return response()->json([
                'message' => 'Error'
            ], $e->getCode());
        }
    }
}
