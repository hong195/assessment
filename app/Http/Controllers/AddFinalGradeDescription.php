<?php

namespace App\Http\Controllers;

use App\Infrastructure\Services\FinalGradeService;
use Illuminate\Http\Request;

class AddFinalGradeDescription extends Controller
{
    private FinalGradeService $finalGradeService;

    public function __construct(FinalGradeService $finalGradeService)
    {
        $this->finalGradeService = $finalGradeService;
    }

    public function store(string $finalGradeId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->finalGradeService->addDescription(
                $finalGradeId,
                $request->get('description')
            );

            return response()->json(['message' => 'Примечание добавлено']);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка добавления примечание'], 422);
        }
    }
}
