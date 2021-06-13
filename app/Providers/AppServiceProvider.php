<?php

namespace App\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Criterion\CriterionRepository;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\Employee\EmployeeRepository;
use Domain\Model\Pharmacy\PharmacyRepository;
use Domain\Model\User\PasswordHasher;
use Domain\Model\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use Infastructure\Persistence\Doctrine\DoctrineCriterionRepository;
use Infastructure\Persistence\Doctrine\DoctrineEmployeeEfficiencyAnalysesRepository;
use Infastructure\Persistence\Doctrine\DoctrineEmployeeRepository;
use Infastructure\Persistence\Doctrine\DoctrinePharmacyRepository;
use Infastructure\Persistence\Doctrine\DoctrineUserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Infastructure\Services\BcryptPasswordHasher;
use Infastructure\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EfficiencyAnalysisRepository::class, function() {
            $em = $this->app->make(EntityManagerInterface::class);
            return new DoctrineEmployeeEfficiencyAnalysesRepository($em);
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
