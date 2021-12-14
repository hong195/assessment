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
    private array $pharmacyIds = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function byYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function byMonth($month): static
    {
        $this->month = $month;
        return $this;
    }

    public function byStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function byPharmacies(array $pharmacyIds): static
    {
        $this->pharmacyIds = $pharmacyIds;
        return $this;
    }

    public function byEmployeeName(string $employeeName): static
    {
        $this->employeeName = $employeeName;
        return $this;
    }

    /**
     * @param int $perPage
     * @return FinalGrade[]
     */
    public function execute(int $perPage = 10000): array
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

        if ($this->pharmacyIds) {
            $queryBuilder
                ->innerJoin(Employee::class, 'e',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'e.id = f.employeeId'
            )
                ->andWhere($queryBuilder->expr()->in('e.pharmacy', $this->pharmacyIds));
        }

        $queryBuilder->setMaxResults($perPage);


        $queryBuilder->orderBy('f.scored/f.total', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
