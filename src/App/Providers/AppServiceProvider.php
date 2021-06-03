<?php

namespace App\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use Infastructure\Persistence\Doctrine\DoctrineEmployeeEfficiencyAnalysesRepository;
use Infastructure\Persistence\Doctrine\DoctrineUserRepository;

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
