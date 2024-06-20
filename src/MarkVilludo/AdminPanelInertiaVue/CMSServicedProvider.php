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
