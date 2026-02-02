<?php
namespace Modules;

use FastRoute\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    private $middlewares = [];

    private $commands = [];

    public function boot()
    {
        $modules = $this->getModules();
    }

    private function getModules()
    {
        $directories = array_map('basename', File::directories(__DIR__));
        return $directories;
    }

    // register modules
    private function registerModule($module)
    {
        $modulePath = __DIR__ . "/{module}";
        /** Init Routes **/
        if (File::exists($modulePath . '/routes.php')) {
            $this->loadRoutesFrom($modulePath . '/routes.php');
        }
        
        /** Init Migrations **/
        if (File::exists($modulePath . '/migration')) {
            $this->loadMigrationsFrom($modulePath . '/migrations');
        }

        /** Init Languages **/
        if (File::exists($modulePath . '/lang')) {
            $this->loadTranslationsFrom($modulePath . '/lang', '{module}');
            $this->loadJsonTranslationsFrom($modulePath . '/lang');
        }

        /** Init Views **/
        if (File::exists($modulePath. '/resources/views')) {
            $this->loadViewsFrom($modulePath.'/resources/views', strtolower($module));
        }

        /** Init Helpers **/
        if (File::exists($modulePath. '/helpers')) {
            $helperList = File::allFiles($modulePath. '/helpers');
            if (!empty($helperList)) {
                foreach ($helperList as $helper) {
                    $file = $helper->getPathName();
                    require $file;
                }
            }
        }
    }

    private function registerConfig($module)
    {
        $configPath = __DIR__ . '/' . $module . '/configs';
        if (File::exists($configPath)) {
            $configFiles = array_map('basename', File::allFiles($configPath));
            foreach ($configFiles as $configFile) {
                $alias = basename($configFile, '.php');
                $this->mergeConfigFrom($configPath . '/' . $configFile, $alias);
            }
        }
    }

    private function registerMiddleware()
    {
        if (empty($this->middlewares)) {
            return;
        }
        foreach ($this->middlewares as $middleware) {
            $this->app->bind($middleware['class'], $middleware['class']);
        }
    }
}