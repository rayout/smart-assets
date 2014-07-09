<?php namespace Kodeks\SmartAssets;

use Illuminate\Support\ServiceProvider;
use Kodeks\SmartAssets\Filters\ClientCacheFilter;
use Kodeks\SmartAssets\File\FileParser;
use Kodeks\SmartAssets\File\FileGenerator;

class SmartAssetsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerBladeExtensions();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->package('kodeks/smart-assets');

		include __DIR__.'/../../routes.php';

		$this->app['asset'] = $this->app->share(function($app)
		{

			$config = $app->config->get('smart-assets::config');
			$config['base_path'] = base_path();
			$config['env'] = $app['env'];

			$parser = new FileParser($config);
			$generator = new FileGenerator($config);

			$pipeline = new AssetPipeline($parser, $generator);

			// let other packages hook into pipeline configuration
			$app['events']->fire('asset.pipeline.boot', $pipeline);

			return $pipeline;
		});

		$this->app['assets.clean'] = $this->app->share(function($app)
		{
			return new Commands\AssetsCleanCommand;
		});

		$this->commands('assets.clean');
	}

	/**
	 * Register custom blade extensions
	 *  - @stylesheets()
	 *  - @javascripts()
	 *
	 * @return void
	 */
	protected function registerBladeExtensions()
	{

		$config = $this->app->config->get('smart-assets::config');
		$config['base_path'] = base_path();
		$config['env'] = $this->app['env'];

		//env?
		if(in_array($config['env'], $config['concat'])){

			$blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();
			$blade->extend(function($value, $compiler) use ($config)
			{
				$generator = new FileGenerator($config);
				$parser = new FileParser($config);

				return preg_replace_callback('/<!--\s*build:(js|css)\s*(.*?)\s*-->(.*?)<!--\s*endbuild\s*-->/usi',
					function($match) use ($generator,$parser,$config, $value){

						list($find, $mime, $file_name, $files) = $match;

						if(!empty($mime) && !empty($file_name) && !empty($files)) {

							preg_match_all('/(src|href)="(.*?)"/usi', $files, $files);

							if($mime == 'js') $mime = 'javascripts';
							if($mime == 'css') $mime = 'stylesheets';

							if(!empty($files[0])) {
								$files_absolute_path = [];

								foreach ($files[2] as $file) {
									$file = str_replace('/pipeline', '', $file);

									$files_absolute_path[] = $generator->file($parser->absolutePath($file, $mime));
								}

								$result = $generator->concatenateFiles($mime, $files_absolute_path);
								if(!empty($result)){
									$version = $parser->saveFile($file_name, $result, $config['concat_add_version']);
									$version = !empty($version) ? '?v='.$version : '';

									if($mime == 'javascripts'){
										$inline = '<script type="text/javascript" src="' . $file_name . $version . '"></script>';
									}

									if($mime == 'stylesheets'){
										$inline = '<link rel="stylesheet" type="text/css" href="' . $file_name . $version . '" />';
									}

									return $inline;
								}
							}
						}

						return $value;
					},
				$value
				);
			});
		}

	}

}

