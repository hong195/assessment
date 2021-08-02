<?php

namespace App\Http\Controllers;

use App\Http\Resources\FinalGradeResource;
use App\Infrastructure\Services\PharmacyFinalGradeService;

class CompletedPharmacyFinalGradeController extends Controller
{
    private PharmacyFinalGradeService $finalGradeService;

    public function __construct(PharmacyFinalGradeService $finalGradeService)
    {
        $this->finalGradeService = $finalGradeService;
    }

    public function show()
    {
        FinalGradeResource::withoutWrapping();

        return collect($this->finalGradeService->getCompletedYearlyPharmacyFinalGrades())
            ->map(function ($el) {
                if (empty($el)) {
                    return [];
                }
                return FinalGradeResource::make($el);
            });
    }
}
