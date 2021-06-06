<?php


namespace Tests\Feature;


use Illuminate\Support\Facades\Artisan;

trait DoctrineMigrationsTrait
{
    public function resetMigrations()
    {
        Artisan::call('doctrine:migrations:refresh --connection="testing"');
    }
}
