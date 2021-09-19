<?php

namespace App\Providers;

use App\Domain\Listeners\UserListener;
use App\Domain\Model\SaleManager\SaleManagerRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineSaleManagerRepository;
use App\Infrastructure\Services\FinalGradesQuery;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\Criterion\CriterionRepository;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Domain\Model\User\PasswordHasher;
use App\Domain\Model\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Persistence\Doctrine\DoctrineCriterionRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineFinalGradeRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineEmployeeRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrinePharmacyRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use App\Infrastructure\Services\BcryptPasswordHasher;
use App\Infrastructure\Services\UserService;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->bind(FinalGradeRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrineFinalGradeRepository($em);
        });

        $this->app->bind(UserRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrineUserRepository($em);
        });

        $this->app->bind(CriterionRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrineCriterionRepository($em);
        });

        $this->app->bind(PharmacyRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrinePharmacyRepository($em);
        });

        $this->app->bind(EmployeeRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrineEmployeeRepository($em);
        });

        $this->app->bind(PasswordHasher::class, function() {
            $hasher = $this->app->make(Hasher::class);
            return new BcryptPasswordHasher($hasher);
        });

        $this->app->bind(UserService::class, function() {
            $hasher = $this->app->make(PasswordHasher::class);
            $em = $this->app->make(EntityManagerInterface::class);
            $userRepository = $this->app->make(UserRepository::class);

            return new UserService($userRepository, $em, $hasher);
        });

        $this->app->bind(Serializer::class, function() {
            $encoders = [new JsonEncoder(), new JsonDecode()];
            $normalizers = [new ObjectNormalizer()];

            return new Serializer($normalizers, $encoders);
        });

        $this->app->bind(FinalGradesQuery::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            $finalGradeQuery = new FinalGradesQuery($em);

            if ($year = request('year')) {
                $finalGradeQuery->byYear($year);
            }

            if ($month = request('month')) {
                $finalGradeQuery->byMonth($month);
            }

            if ($status = request('status')) {
                $finalGradeQuery->byStatus($status);
            }

            if ($pharmacyId = request('pharmacyId')) {
                $finalGradeQuery->byPharmacy($pharmacyId);
            }

            return $finalGradeQuery;
        });

        $this->app->bind(SaleManagerRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrineSaleManagerRepository($em);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->app->make(EntityManagerInterface::class);
        $saleMangerRepo = $this->app->make(SaleManagerRepository::class);
        $userListener = $this->app->makeWith(UserListener::class, [$saleMangerRepo, $em]);
        $em->getConfiguration()->getEntityListenerResolver()->register($userListener);
    }
}
