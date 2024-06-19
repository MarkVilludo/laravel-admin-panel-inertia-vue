<?php 

namespace MarkVilludo\AdminPanelInertiaVue;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class CMSServicedProvider extends BaseServiceProvider
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
            __DIR__.'/../resources/assets' =>  resource_path('/'),
        ],'assets');

        $this->publishes([
            __DIR__.'/../resources/Layouts' =>  resource_path('/js'),
        ],'layouts');
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
