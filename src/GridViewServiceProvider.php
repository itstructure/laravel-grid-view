<?php

namespace Itstructure\GridView;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class GridViewServiceProvider
 * @package Itstructure\GridView
 */
class GridViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom($this->packagePath('resources/views'), 'grid_view');
        $this->loadTranslationsFrom($this->packagePath('resources/lang'), 'grid_view');

        require_once __DIR__ . '/functions.php';

        Blade::directive('gridView', function ($config) {
            return "<?php echo grid_view($config); ?>";
        });
    }

    /**
     * @param $path
     * @return string
     */
    private function packagePath($path): string
    {
        return __DIR__ . "/../" . $path;
    }
}
