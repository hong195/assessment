<?php

namespace App\Http\Controllers;

use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Http\Resources\EmployeeResource;

class PharmacyEmployeesController extends Controller
{
    private PharmacyRepository $pharmacyRepository;

    public function __construct(PharmacyRepository $pharmacyRepository)
    {
        $this->pharmacyRepository = $pharmacyRepository;
    }

    /**
     * @throws \App\Exceptions\NotFoundEntityException
     */
    public function index(string $pharmacyId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        /** @var Pharmacy $pharmacy */
        $pharmacy = $this->pharmacyRepository->findOrFail($pharmacyId);
        $employees = $pharmacy->getEmployees();

        return EmployeeResource::collection($employees->toArray());
    }
}
