<?php

namespace App\Jobs;

use App\Domain\Model\Employee\Employee;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\FinalGrade\Month;
use App\Infrastructure\Services\FinalGradesQuery;
use App\Notifications\FinalGradeCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendToPharmacyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        private string $employeeId,
        private Month $month
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employeeRepository = app()->make(EmployeeRepository::class);
        $finalGradeRepository = app()->make(FinalGradesQuery::class);
        /** @var Employee $employee */
        $employee = $employeeRepository->getById(new EmployeeId($this->employeeId));
        $pharmacy  = $employee->getPharmacy();
        $pharmacyEmail = (string) $pharmacy->getEmail();

        if (!$pharmacyEmail) {
            return;
        }

        $finalGrades = $finalGradeRepository->byStatus('completed')
                        ->byPharmacies([(string) $pharmacy->getId()])
                        ->byYear($this->month->getYear())
                        ->byMonth($this->month->getMonth())
                        ->byStatus('completed')
                        ->execute();

        $employees = $pharmacy->getEmployees()->toArray();

        Notification::route('mail', [(string) $pharmacy->getEmail()])
            ->notify(new FinalGradeCompleted($finalGrades, $employees));
    }
}
