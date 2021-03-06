<?php

namespace JuHeData\CasLogin;

use Illuminate\Auth\RequestGuard;
use Illuminate\Support\ServiceProvider;
use JuHeData\CasLogin\Middleware\AuthCheckMiddleware;

class CasLoginServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/juheCas.php', 'juheCas');
        }

        // 注册publish命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\PublishCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([__DIR__ . '/../config/juheCas.php' => config_path('juheCas.php')]);

            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/../database/create_ext_users_table.php.stub' => database_path("/migrations/{$timestamp}_create_ext_users_table.php"),
            ], 'migrations');

            $this->publishes([__DIR__ . '/views/cas_login_error.blade.php' => resource_path('views/errors/cas_login_error.blade.php')]);

        }
        $this->registerRoutes();
    }

    /**
     * Register the Horizon routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }
}
