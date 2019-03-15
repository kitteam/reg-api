<?php

namespace RegApi\Providers;

use Illuminate\Support\ServiceProvider;

class RegServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot()
    {
        $this->registerConfig();
    }

    /**
     * Register config.
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/reg.php' => config_path('reg.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/reg.php', 'reg'
        );
    }
}
