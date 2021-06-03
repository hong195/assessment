<?php

namespace App\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Illuminate\Support\ServiceProvider;
use Infastructure\Persistence\Doctrine\DoctrineEmployeeEfficiencyAnalysesRepository;

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
