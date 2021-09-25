<?php namespace albreis\cms;

use albreis\cms\commands\CmsVersionCommand;
use albreis\cms\commands\Mailqueues;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use albreis\cms\commands\CmsInstallationCommand;
use albreis\cms\commands\CmsUpdateCommand;
use Illuminate\Foundation\AliasLoader;
use App;

class CMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * Call when after all packages has been loaded
     *
     * @return void
     */

    public function boot()
    {        
                                
        $this->loadViewsFrom(__DIR__.'/views', 'cms');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/localization','cms');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if($this->app->runningInConsole()) {
            $this->registerSeedsFrom(__DIR__.'/database/seeds');
            $this->publishes([__DIR__.'/views' => base_path('resources/views/vendor/cms')],'cb_views');
            $this->publishes([__DIR__.'/configs/cms.php' => config_path('cms.php')],'cb_config');
            $this->publishes([__DIR__.'/userfiles/controllers/CMSHook.php' => app_path('Http/Controllers/CMSHook.php')],'CMSHook');
            $this->publishes([__DIR__.'/userfiles/controllers/AdminCmsUsersController.php' => app_path('Http/Controllers/AdminCmsUsersController.php')],'cb_user_controller');
            $this->publishes([__DIR__.'/assets'=>public_path('vendor/cms')],'cb_asset');
        }

        $this->customValidation();
    }

    /**
     * Register the application services.
     * Call when this package is first time loaded
     *
     * @return void
     */
    public function register()
    {                                   
        require __DIR__.'/helpers/Helper.php';      

        $this->mergeConfigFrom(__DIR__.'/configs/cms.php','cms');

        $this->registerSingleton();

        if($this->app->runningInConsole()) {
            $this->commands('cmsinstall');
            $this->commands('cmsupdate');
            $this->commands('cmsVersionCommand');
            $this->commands('cmsMailQueue');
        }

        $loader = AliasLoader::getInstance();
        $loader->alias('PDF', 'Barryvdh\DomPDF\Facade');
        $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        $loader->alias('Image', 'Intervention\Image\ImageManagerStatic');
        $loader->alias('CMS', 'albreis\cms\helpers\CMS');
        $loader->alias('CMSHelper', 'albreis\cms\helpers\CMSHelper');
    }
   
    private function registerSingleton()
    {
        $this->app->singleton('cms', function ()
        {
            return true;
        });

        $this->app->singleton('cmsinstall',function() {
            return new CmsInstallationCommand;
        });
        
        $this->app->singleton('cmsupdate',function() {
            return new CmsUpdateCommand;
        });

        $this->app->singleton("cmsVersionCommand", function() {
            return new CmsVersionCommand;
        });

        $this->app->singleton("cmsMailQueue", function() {
            return new Mailqueues;
        });
    }

    protected function registerSeedsFrom($path)
    {
        foreach (glob("$path/*.php") as $filename)
        {
            include $filename;
            $classes = get_declared_classes();
            $class = end($classes);

            $command = request()->server('argv', null);
            if (is_array($command)) {
                $command = implode(' ', $command);
                if ($command == "artisan db:seed") {
                    Artisan::call('db:seed', ['--class' => $class]);
                }
            }

        }
    }

    private function customValidation() {
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);
        },'The :attribute should be letters and spaces only');

        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            // This will only accept alphanumeric and spaces.
            return preg_match('/^[a-zA-Z0-9\s]+$/', $value);
        },'The :attribute should be alphanumeric characters and spaces only');
    }
}
