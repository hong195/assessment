<?php

namespace App\Http\Controllers;

use App\Domain\Model\User\UserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Infrastructure\Services\UserService;

class UsersController extends Controller
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection($this->userRepository->getAll()->toArray());
    }

    public function store(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->userService->addUser($request->getDto());

            return response()->json([
                'message' => 'Пользователь создан'
            ]);

        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show($id): UserResource
    {
        return UserResource::make($this->userRepository->findOrFail($id));
    }

    public function update(UserRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->userService->updateUser($id, $request->getDto());

            return response()->json([
                'message' => 'Пользователь обновлен'
            ]);

        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'message' => 'Пользователь удален'
            ]);

        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
