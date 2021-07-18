<?php

namespace App\Http\Controllers;

use App\Domain\Model\Employee\EmployeeRepository;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Infrastructure\Services\EmployeeService;
use Doctrine\ORM\EntityManagerInterface;

class EmployeesController extends Controller
{
    private EntityManagerInterface $em;
    private EmployeeRepository $employeeRepository;
    private EmployeeService $employeeService;

    public function __construct(EntityManagerInterface $em, EmployeeRepository $employeeRepository,
        EmployeeService $employeeService)
    {
        $this->em = $em;
        $this->employeeRepository = $employeeRepository;
        $this->employeeService = $employeeService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->employeeRepository->all()->toArray());
    }

    public function store(EmployeeRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->employeeService->create($request->getDto());
            return response()->json([
                'message' => 'Created'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show($id): EmployeeResource
    {
        return EmployeeResource::make($this->employeeRepository->findOrFail($id));
    }

    public function update($id, EmployeeRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->employeeService->update($id, $request->getDto());
            return response()->json([
                'message' => 'Updated'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->employeeService->destroy($id);
            return response()->json([
                'message' => 'Destroyed'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
