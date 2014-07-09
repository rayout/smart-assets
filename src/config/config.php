<?php

/*
|--------------------------------------------------------------------------
| EnvironmentFilter
|--------------------------------------------------------------------------
|
| This is used to run filters on specific environments. For example, if you
| only want to run a filter on production and staging environments
|
| new EnvironmentFilter(new FilterExample, App::environment(), array('production', 'staging')),
|
*/
use Kodeks\SmartAssets\Filters\EnvironmentFilter;

return array(

	/*
	|--------------------------------------------------------------------------
	| routing array
	|--------------------------------------------------------------------------
	|
	| This is passed to the Route::group and allows us to group and filter the
	| routes for our package
	|
	*/
	'routing' => array(
		'prefix' => '/pipeline'
	),

	/*
	|--------------------------------------------------------------------------
	| paths
	|--------------------------------------------------------------------------
	|
	| Здесь указываем директории из которых можно(!) брать файлы.
	|
	*/
	'paths' => array(
		'public'
	),

	/*
	|--------------------------------------------------------------------------
	| modules
	|--------------------------------------------------------------------------
	|
	| Find files in modules? Package https://github.com/creolab/laravel-modules
	| First search in "paths". If not found get modules
	| In production environment gets files from  public/packages/module/
	| In others environment gets from app/modules/
	|
	*/
	'modules' => true,

	/*
	|--------------------------------------------------------------------------
	| mimes
	|--------------------------------------------------------------------------
	|
	| In order to know which mime type to send back to the server
	| we need to know if it is a javascript or stylesheet type. If
	| the extension is not found below then we just return a 404
	|
	*/
	'mimes' => array(
		'javascripts' => array('.js', '.js.coffee', '.coffee', '.min.js'),
		'stylesheets' => array('.css', '.css.less', '.css.sass', '.css.scss', '.less', '.sass', '.scss', '.min.css'),
	),

	/*
	|--------------------------------------------------------------------------
	| filters
	|--------------------------------------------------------------------------
	|
	| In order for a file to be included, it needs to be listed
	| here and we can also do any preprocessing on files with the extension if
	| we choose to.
	|
	*/
	'filters' => array(
		'.min.js' => array(

		),
		'.min.css' => array(

		),
		'.js' => array(
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\JSMinPlusFilter, App::environment()),
		),
		'.js.coffee' => array(
			new Kodeks\SmartAssets\Filters\CoffeeScript,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\JSMinPlusFilter, App::environment()),
		),
		'.coffee' => array(
			new Kodeks\SmartAssets\Filters\CoffeeScript,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\JSMinPlusFilter, App::environment()),
		),
		'.css' => array(
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		),
		'.css.less' => array(
			new Kodeks\SmartAssets\Filters\LessphpFilter,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		),
		'.css.sass' => array(
			new Kodeks\SmartAssets\Filters\SassFilter,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		),
		'.css.scss' => array(
			new Assetic\Filter\ScssphpFilter,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		),
		'.less' => array(
			new Kodeks\SmartAssets\Filters\LessphpFilter,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		),
		'.sass' => array(
			new Kodeks\SmartAssets\Filters\SassFilter,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		),
		'.scss' => array(
			new Assetic\Filter\ScssphpFilter,
			new EnvironmentFilter(new Kodeks\SmartAssets\Filters\CssMinFilter, App::environment()),
		)
	),

	/*
	|--------------------------------------------------------------------------
	| cache
	|--------------------------------------------------------------------------
	|
	| By default we cache assets on production environment permanently. We also cache
	| all files using the `cache_server` driver below but the cache is busted anytime
	| those files are modified.
	|
	*/
	'cache' => 	array('production', 'local'),


	/*
	|--------------------------------------------------------------------------
	| concat
	|--------------------------------------------------------------------------
	|
	| Compiling design allows the use of the form:
	|
	| <!-- build:js /lib/js/vendor.min.js -->
	| <script type="text/javascript" src="/lib/js/ng/angular.js"></script>
	| <script type="text/javascript" src="/lib/js/ng/angular-animate.js"></script>
	| <!-- endbuild -->
	|
	*/
	'concat' => array('production'),


	/*
	|--------------------------------------------------------------------------
	| concat_add_version
	|--------------------------------------------------------------------------
	|
	| Automatic add version to file (?v=123456) when concatenate
	|
	*/

	'concat_add_version' => true,

	/*
	|--------------------------------------------------------------------------
	| concat_global_filters
	|--------------------------------------------------------------------------
	|
	| When concatenation is turned on, assets are filtered
	| and we can do global filters on the resulting dump file. This would be
	| useful if you wanted to apply a filter to all javascript or stylesheet files
	| like minification. Out of the box we don't have any filters here. Add at
	| your own risk. I don't put minification filters here because the minify
	| doesn't always work perfectly and can bjork your entire concatenated
	| javascript or stylesheet file if it messes up.
	|
	| It is probably safe just to leave this alone unless you are familar with
	| what is actually going on here.
	|
	*/
	'concat_global_filters' => array(
		'javascripts' => array(),
		'stylesheets' => array(),
	),

	/*
	|--------------------------------------------------------------------------
	| cache_server
	|--------------------------------------------------------------------------
	|
	| You can create your own CacheInterface if the filesystem cache is not up to
	| your standards. This is for caching asset files on the server-side.
	|
	| Please note that caching is used on **ALL** environments always. This is done
	| to increase performance of the pipeline. Cached files will be busted when the
	| file changes.
	|
	| However, manifest files are regenerated (not cached) when the environment is
	| not found within the 'cache' array. This lets you develop on local and still
	| utilize caching, so you don't have to regenerate all precompiled files while
	| developing on your assets.
	|
	| See more in CacheInterface.php at
	|
	|    https://github.com/kriswallsmith/assetic/blob/master/src/Assetic/Cache
	|
	|
	*/
	'cache_server' => new Assetic\Cache\FilesystemCache(App::make('path.storage') . '/cache/smart-assets'),

	/*
	|--------------------------------------------------------------------------
	| cache_client
	|--------------------------------------------------------------------------
	|
	| If you want to handle 304's and what not, to keep users from refetching
	| your assets and saving your bandwidth you can use a cache_client driver
	| that handles this. This doesn't handle assets on the server-side, use
	| cache_server for that. This only works when the current environment is
	| listed within `cache`
	|
	| Note that this needs to implement the interface
	|
	|	new Kodeks\SmartAssets\Filters\ClientCacheFilter
	|
	| or this won't work correctly. It is a wrapper class around your cache_server
	| driver and also uses the AssetCache class to help access files.
	|
	*/
	'cache_client' => new Kodeks\SmartAssets\Filters\ClientCacheFilter,

	/*
	|--------------------------------------------------------------------------
	| controller_action
	|--------------------------------------------------------------------------
	|
	| Asset pipeline will route all requests through the controller action
	| listed here. This allows us to completely control how the controller
	| should behave for incoming requests for assets.
	|
	| It is probably safe just to leave this alone unless you are familar with
	| what is actually going on here.
	|
	*/
	'controller_action' => '\Kodeks\SmartAssets\SmartAssetsController@file',



);
