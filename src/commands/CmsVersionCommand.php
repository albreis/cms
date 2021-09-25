<?php namespace albreis\cms\commands;

use App;
use Illuminate\Console\Command;

class CmsVersionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CMS Version Command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public static $version = "5.5.7";

    public function handle()
    {
        $this->info(static::$version);
    }
}
