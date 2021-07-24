<?php

namespace App\Http\Controllers;

use App\Domain\Model\Criterion\Criterion;
use App\Domain\Model\Criterion\CriterionRepository;
use App\Domain\Model\Criterion\Option;
use App\Http\Requests\CriterionOptionRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\DomainException;
use App\Infrastructure\Services\CriterionService;

class CriteriaOptionsController extends Controller
{
    private CriterionService $criterionService;
    private EntityManagerInterface $em;
    private CriterionRepository $criterionRepository;

    public function __construct(CriterionService $criterionService, EntityManagerInterface $em)
    {
        $this->criterionService = $criterionService;
        $this->em = $em;
    }

    public function index(string $criterionId): \Illuminate\Http\JsonResponse
    {
        $options = $this->criterionService->getOptions($criterionId)->toArray();
        return response()->json(collect($options)->map(function (Option $option) {
            return [
                'id' => (string)$option->getId(),
                'name' => $option->getName(),
                'value' => $option->getValue()
            ];
        }));
    }

    public function store(CriterionOptionRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->criterionService->addOption($id, $request->getDto());
            $this->em->flush();

            return response()->json([
                'message' => 'Created'
            ]);

        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * @throws \App\Exceptions\NotFoundEntityException
     */
    public function show(string $criterionId, string $optionId)
    {
        $option = $this->criterionService->getOption($criterionId, $optionId);

        return [
            'id' => (string) $option->getId(),
            'name' => $option->getName(),
            'value' => $option->getValue()
        ];
    }

    public function update(CriterionOptionRequest $request, $criterionId, $optionId): \Illuminate\Http\JsonResponse
    {
        try {
            $this->criterionService->updateOption($criterionId, $optionId, $request->getDto());
            $this->em->flush();

            return response()->json([
                'message' => 'Updated'
            ]);

        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($criterionId, $optionId): \Illuminate\Http\JsonResponse
    {
        try {
            $this->criterionService->removeOption($criterionId, $optionId);

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
