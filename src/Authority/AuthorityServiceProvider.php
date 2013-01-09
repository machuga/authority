<?php

namespace Authority;

use Illuminate\Support\ServiceProvider;

class AuthorityServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['authority'] = $this->app->share(function($app)
        {
            return new Authority($app);
        });
    }

}