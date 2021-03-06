<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriterionOptionRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\DomainException;
use App\Infrastructure\Services\CriterionService;

class CriteriaOptionsController extends Controller
{
    private CriterionService $criterionService;
    private EntityManagerInterface $em;

    public function __construct(CriterionService $criterionService, EntityManagerInterface $em)
    {
        $this->criterionService = $criterionService;
        $this->em = $em;
    }

    public function store(CriterionOptionRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->criterionService->addOption($id, $request->getDto());
            $this->em->flush();

            return response()->json([
                'message' => 'Created'
            ]);

        }catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update(CriterionOptionRequest $request, $criterionId, $optionId): \Illuminate\Http\JsonResponse
    {
        try {
            $this->criterionService->updateOption($criterionId, $optionId, $request->getDto());
            $this->em->flush();

            return response()->json([
                'message' => 'Updated'
            ]);

        }catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($id)
    {
        //
    }
}
