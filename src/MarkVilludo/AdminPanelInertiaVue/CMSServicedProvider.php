<?php 

namespace MarkVilludo\AdminPanelInertiaVue;

use Illuminate\Support\ServiceProvider;

class CMSServicedProvider extends ServiceProvider
{
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {      
        // publish assets and layouts
        $this->publishes([
            __DIR__.'/../../../resources/assets' =>  resource_path('assets/'),
            __DIR__.'/../../../resources/Layouts' =>  resource_path('/js/Layouts'),
        ],'cms-assets');

        //for usermanagement
        $this->publishes([
            //vue file
            __DIR__.'/../../../resources/Pages' =>  resource_path('/js/Pages/UserManagement'),

            //controllers
            __DIR__.'/../../../resources/Controllers' => app_path('Http/Controllers/UserManagement'),

            //services
            __DIR__.'/../../../resources/Services' => app_path('Services'),

            //request
            __DIR__.'/../../../resources/Requests' => app_path('Http/Requests'),

            //migration
            // __DIR__.'/../../../resources/migrations' => database_path('migrations'),

            //seeders
            __DIR__.'/../../../resources/seeders' => database_path('seeders'),
            
            //routes
             __DIR__.'/../../../resources/routes' => base_path('routes'),
        ],'cms-usermanagement');
        //end
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       
    }

}
