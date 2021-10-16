<?php

namespace App\Jobs;

use App\Domain\Model\Employee\Employee;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Infrastructure\Services\FinalGradesQuery;
use App\Notifications\FinalGradeCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendToPharmacyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private EmployeeRepository $employeeRepository;

    private FinalGradesQuery $finalGradeRepository;
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(
        private string $employeeId
    )
    {
        $this->employeeRepository = app()->make(EmployeeRepository::class);
        $this->finalGradeRepository = app()->make(FinalGradesQuery::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Employee $employee */
        $employee = $this->employeeRepository->find($this->employeeId);
        $pharmacy  = $employee->getPharmacy();

        $finalGrades = $this->finalGradeRepository->byStatus('completed')
                        ->byPharmacies([(string) $pharmacy->getId()])
                        ->byYear(now()->year)
                        ->byMonth(now()->month)
                        ->execute();

        Notification::send((string) $pharmacy->getEmail(), new FinalGradeCompleted($finalGrades));
    }
}
