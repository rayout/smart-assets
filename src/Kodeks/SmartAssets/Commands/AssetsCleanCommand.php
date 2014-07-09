<?php namespace Kodeks\SmartAssets\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AssetsCleanCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'assets:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cleans out all your cached assets and views";

	/**
	 * The file system instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;


	/**
     * Construct a new AssetsCleanCommand
     */
    public function __construct()
    {
	    parent::__construct();
	    $this->files = new \Illuminate\Filesystem\Filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
	    $this->call('cache:clear');

	    foreach ($this->files->files(storage_path().'/views') as $file)
	    {
		    $this->files->delete($file);
	    }

	    $this->info('Views deleted from cache');
    }

}
