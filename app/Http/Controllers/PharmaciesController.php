<?php

namespace App\Http\Controllers;

use App\Http\Requests\PharmacyRequest;
use App\Http\Resources\PharmacyResource;
use Domain\Exceptions\DomainException;
use Domain\Model\Pharmacy\PharmacyRepository;
use Infastructure\Exceptions\InfrastructureException;
use Infastructure\Services\PharmacyService;

class PharmaciesController extends Controller
{
    private PharmacyRepository $pharmacyRepository;
    private PharmacyService $pharmacyService;

    public function __construct(PharmacyRepository $pharmacyRepository,
        PharmacyService $pharmacyService)
    {
        $this->pharmacyRepository = $pharmacyRepository;
        $this->pharmacyService = $pharmacyService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PharmacyResource::collection($this->pharmacyRepository->all()->toArray());
    }

    public function store(PharmacyRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $dto = $request->getDto();
            $this->pharmacyService->addPharmacy($dto);

            return response()->json([
                'message' => 'Created'
            ]);
        }catch (DomainException|InfrastructureException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show($id): PharmacyResource
    {
        return PharmacyResource::make($this->pharmacyRepository->findOrFail($id));
    }


    public function update(PharmacyRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $dto = $request->getDto();
            $this->pharmacyService->updatePharmacy($id, $dto);

            return response()->json([
                'message' => 'Updated'
            ]);
        }catch (DomainException|InfrastructureException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->pharmacyService->deletePharmacy($id);

            return response()->json([
                'message' => 'Deleted'
            ]);
        }catch (DomainException|InfrastructureException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
