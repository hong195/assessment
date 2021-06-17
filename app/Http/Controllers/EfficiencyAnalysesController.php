<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEfficiencyAnalysesRequest;
use App\Http\Resources\EfficiencyAnalysesResource;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\DomainException;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Illuminate\Http\Request;
use Infastructure\Exceptions\InfrastructureException;
use Infastructure\Services\EfficiencyAnalysisService;

class EfficiencyAnalysesController extends Controller
{
    private EfficiencyAnalysisRepository $repository;
    private EntityManagerInterface $em;
    private EfficiencyAnalysisService $analysisService;

    public function __construct(EfficiencyAnalysisRepository $repository,
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
