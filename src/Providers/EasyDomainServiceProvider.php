<?php

namespace JoshHudnall\EasyDomain\Providers;

use Illuminate\Support\ServiceProvider;
use JoshHudnall\EasyDomain\Commands\CreateDomain;

class EasyDomainServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    CreateDomain::class,
                ],
            );
        }

        $this->publishes([
            __DIR__.'/../Config/easydomain.php' => config_path('easydomain.php'),
        ], 'easydomain-config');

        $this->publishes([
            __DIR__.'/../Stubs' => resource_path('DomainStubs'),
        ], 'easydomain-stubs');
    }

    public function register(): void
    {
        parent::register();
            $this->mergeConfigFrom(
            __DIR__.'/../config/easydomain.php', 'easydomain'
        );
    }
}
