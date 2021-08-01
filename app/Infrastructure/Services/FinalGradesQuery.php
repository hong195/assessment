<?php


namespace App\Infrastructure\Services;


use App\Domain\Model\Employee\Employee;
use App\Domain\Model\FinalGrade\FinalGrade;
use Doctrine\ORM\EntityManagerInterface;

class FinalGradesQuery
{
    private EntityManagerInterface $entityManager;
    private ?int $month = null;
    private ?int $year = null;
    private ?string $status = null;
    private ?string $pharmacyId = null;
    private ?string $employeeName = null;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function byYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function byMonth(int $month): static
    {
        $this->month = $month;
        return $this;
    }

    public function byStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function byPharmacy(string $pharmacyId): static
    {
        $this->pharmacyId = $pharmacyId;
        return $this;
    }

    public function byEmployeeName(string $employeeName): static
    {
        $this->employeeName = $employeeName;
        return $this;
    }

    public function execute()
    {
        $queryBuilder = $this->entityManager
            ->getRepository(FinalGrade::class)
            ->createQueryBuilder('f')
            ->select('f');

        if ($this->year) {
            $queryBuilder->where('YEAR(f.month.date) = :year')
                ->setParameter('year', $this->year);
        }

        if ($this->month) {
            $queryBuilder->andWhere('MONTH(f.month.date) = :month')
                ->setParameter('month', $this->month);
        }

        if ($this->status) {
            $queryBuilder->andWhere('f.status.status = :status')
                ->setParameter('status', $this->status);
        }

        if ($this->pharmacyId) {
            $queryBuilder
                ->innerJoin(Employee::class, 'e',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'e.id = f.employeeId'
            )
                ->andWhere('e.pharmacy = :pharmacyId')
                ->setParameter('pharmacyId', $this->pharmacyId);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
