<?php
namespace Modules;

use FastRoute\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
    }
}