<?php namespace Kodeks\SmartAssets\File;

use ReflectionClass, RecursiveIteratorIterator, RecursiveDirectoryIterator;
use Assetic\Asset\FileAsset;
use Assetic\Asset\AssetCollection;
use Kodeks\SmartAssets\Filters;

class FileGenerator
{
	/**
	 * Create a new sprockets generator. This will apply the
	 * sprockets assetic filter to generate parse out
	 *
	 * @param array $config [description]
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Return the javascript text for this absolutePath
	 *
	 * @param  string $absolutePath
	 * @return string
	 */
	public function javascript($absolutePath)
	{
		return $this->cached($absolutePath)->dump();
	}

	/**
	 * Return the stylesheet text for this
	 *
	 * @param  $absolutePath
	 * @return string
	 */
	public function stylesheet($absolutePath)
	{
		return $this->cached($absolutePath)->dump();
	}

	/**
	 * Returns this file for $absolutePath
	 *
	 * @param  filepath $absolutePath
	 * @return FileAsset
	 */
	public function file($absolutePath)
	{
		return new FileAsset($absolutePath, $this->filters($absolutePath));
	}

	public function collection($files, $global_filters){
		return new AssetCollection($files, $global_filters);
	}

	public function concatenateFiles($mime, $files){
		$global_filters = $this->config["concat_global_filters"][$mime];
		if ($mime == 'javascripts') {
			$global_filters = array_merge($global_filters, array(new Filters\JavascriptConcatenationFilter));
		}

		$collection = $this->collection($files, $global_filters);

		return $collection->dump();
	}


	/**
	 * Returns the cached version of this absolutePath
	 *
	 * @param  string $absolutePath
	 * @return string
	 */
	public function cached($absolutePath)
	{
		$file = $this->file($absolutePath);

		if (!in_array($this->config['env'], $this->config['cache']))
		{
			return $file;
		}

		$client = $this->config['cache_client'];

		$server = $this->config['cache_server'];

		$cache = new FileCache($file, $client);

		$client->setServerCache($server);

		$client->setAssetCache($cache);

		return $cache;
	}

	/**
	 * Return the filters for this specific file
	 *
	 * @param  $absolutePath
	 * @return array
	 */
	protected function filters($absolutePath)
	{
		$extension = '.' . pathinfo($absolutePath, PATHINFO_EXTENSION);

		$filters = isset($this->config['filters'][$extension]) ? $this->config['filters'][$extension] : array();


			return $filters;
	}
}
