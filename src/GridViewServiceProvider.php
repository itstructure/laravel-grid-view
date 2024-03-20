<?php

namespace Itstructure\GridView;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Itstructure\GridView\Commands\PublishCommand;

/**
 * Class GridViewServiceProvider
 * @package Itstructure\GridView
 */
class GridViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerCommands();
    }

    public function boot()
    {
        // Loading settings
        $this->loadViews();
        $this->loadTranslations();

        // Publish settings
        $this->publishViews();
        $this->publishTranslations();

        // Directives
        require_once __DIR__ . '/functions.php';

        Blade::directive('gridView', function ($config) {
            return "<?php echo grid_view($config); ?>";
        });
    }


    /*
    |--------------------------------------------------------------------------
    | COMMAND SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Register commands.
     * @return void
     */
    private function registerCommands(): void
    {
        $this->commands(PublishCommand::class);
    }


    /*
    |--------------------------------------------------------------------------
    | LOADING SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Set directory to load views.
     * @return void
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom($this->packagePath('resources/views'), 'grid_view');
    }

    /**
     * Set directory to load translations.
     * @return void
     */
    private function loadTranslations(): void
    {
        $this->loadTranslationsFrom($this->packagePath('resources/lang'), 'grid_view');
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLISH SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Publish views.
     * @return void
     */
    private function publishViews(): void
    {
        $this->publishes([
            $this->packagePath('resources/views') => resource_path('views/vendor/grid_view'),
        ], 'views');
    }

    /**
     * Publish translations.
     * @return void
     */
    private function publishTranslations(): void
    {
        $this->publishes([
            $this->packagePath('resources/lang') => resource_path('lang/vendor/grid_view'),
        ], 'lang');
    }


    /*
    |--------------------------------------------------------------------------
    | OTHER SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * @param $path
     * @return string
     */
    private function packagePath($path): string
    {
        return __DIR__ . "/../" . $path;
    }
}
