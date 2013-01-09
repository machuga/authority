<?php
/**
 * __      ___    _    ___                 _          
 * \ \    / (_)__| |_ | __|__ _  _ _ _  __| |_ _ _  _ 
 *  \ \/\/ /| (_-< ' \| _/ _ \ || | ' \/ _` | '_| || |
 *   \_/\_/ |_/__/_||_|_|\___/\_,_|_||_\__,_|_|  \_, |
 *                                               |__/
 *                                                                               
 * Created by : bngreer
 * Date: 1/8/13
 * Time: 5:38 PM
 * 
 * 
 */
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