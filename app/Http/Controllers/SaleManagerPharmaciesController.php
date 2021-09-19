<?php

namespace App\Http\Controllers;

use App\Domain\Model\SaleManager\SaleManagerRepository;
use App\Http\Requests\SaleManagerPharmaciesRequest;
use App\Http\Resources\SaleManagerResource;
use App\Infrastructure\Services\SaleManagerService;

class SaleManagerPharmaciesController extends Controller
{
    private SaleManagerService $managerService;
    private SaleManagerRepository $repository;

    public function __construct(SaleManagerService $managerService, SaleManagerRepository $repository)
    {
        $this->managerService = $managerService;
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return SaleManagerResource::collection($this->managerService->getSaleManagerList());
    }

    public function store(SaleManagerPharmaciesRequest $request): \Illuminate\Http\JsonResponse
    {
        $dto = $request->getDto();

        try {
            $this->managerService->assingPharmacies($dto);

            return response()->json([
                'message' => 'Список аптек РОП`а обновлены'
            ]);

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Ошибка при назначении аптек, повторите позже',
                'tet' => $e::class
            ], $e->getCode());
        }
    }

    /**
     * @throws \App\Exceptions\NotFoundEntityException
     */
    public function show(int $id): SaleManagerResource
    {
        return SaleManagerResource::make($this->repository->findOrFail($id));
    }
}
