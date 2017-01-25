<?php

namespace Sworup\ProfanityFilter;

use Illuminate\Support\ServiceProvider;

class ProfanityFilterServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/config.php';
        $this->mergeConfigFrom($configPath, 'profanity-filter');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['profanity'] = $this->app->share(function($app) {
            $dependency = $this->app['config']['profanity-filter.words'];
            $blacklist  = $this->app['config']['profanity-filter.blacklist'];
            $replace    = $this->app['config']['profanity-filter.replace'];
            return new ProfanityFilter($dependency, $blacklist, $replace);
        });
    }
}