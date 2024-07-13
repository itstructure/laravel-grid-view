<?php

namespace Itstructure\GridView\Commands;

use Illuminate\Console\Command;
use Itstructure\GridView\GridViewServiceProvider;

/**
 * Class PublishCommand
 *
 * @package Itstructure\GridView\Commands
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'grid_view:publish '.
    '{--force : Overwrite existing files by default.}'.
    '{--only= : Publish only specific part. Available parts: views, lang.}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Publish GridView package parts.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->info('Starting publication process of GridView package parts...');

        $callArguments = ['--provider' => GridViewServiceProvider::class];

        if ($this->option('only')) {
            switch ($this->option('only')) {
                case 'views':
                    $this->info('Publish just a part: views.');
                    $callArguments['--tag'] = 'views';
                    break;

                case 'lang':
                    $this->info('Publish just a part: lang.');
                    $callArguments['--tag'] = 'lang';
                    break;

                default:
                    $this->error('Invalid "only" argument value!');
                    return;
                    break;
            }

        } else {
            $this->info('Publish all parts: views, lang.');
        }

        if ($this->option('force')) {
            $this->warn('Force publishing.');
            $callArguments['--force'] = true;
        }

        $this->call('vendor:publish', $callArguments);
    }
}
