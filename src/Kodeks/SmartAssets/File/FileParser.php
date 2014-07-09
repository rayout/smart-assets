<?php namespace Kodeks\SmartAssets\File;

class FileParser
{
	public $config = null;

	public function __construct($config){
		$this->config = $config;
	}

	/**
	 * We strip off any leading paths and then try to find this
	 * file using our available paths and extensions.
	 *
	 * @param  string $filename
	 * @return string
	 */
	public function absolutePath($original_filename, $mime)
	{
		$path = pathinfo($original_filename, PATHINFO_DIRNAME);
		$filename = pathinfo($original_filename, PATHINFO_FILENAME);
		$extension = '.' . pathinfo($original_filename, PATHINFO_EXTENSION);

		$delimiter = ($original_filename[0] == '/')? '' : '/';

		$absolutePath = null;

		//разрешенный extension?
		if(array_search($extension, $this->config['mimes'][$mime]) !== false) {
			//Составляем путь
			foreach ($this->config['paths'] as $true_path) {
				$absolutePath = $this->fileExists($true_path . $delimiter .$original_filename);
			}
		}

		//если не нашли, смотрим в модулях
		if(empty($absolutePath) and !empty($this->config['modules'])){
			//production?
			if($this->config['env'] == 'production'){
				$module_path = 'public/packages/module';
			}else{
				$module_path = 'app/modules';
			}

			$absolutePath = $this->fileExists($module_path . $delimiter .$original_filename);
		}

		return $absolutePath;
	}

	public function saveFile($filename, $content, $version = false){

		$delimiter = ($filename[0] == '/')? '' : '/';
		$path = $this->config['base_path']. '/public' . $delimiter . $filename;
		file_put_contents($path, $content);

		if($version){
			return filemtime($path);
		}else{
			return '';
		}
	}


	/**
	 * If the file exists then we will return the filename
	 * else we return null
	 *
	 * @param  string $filename
	 * @return null|$filename
	 */
	private function fileExists($filename)
	{
		$filename = $this->config['base_path'] . '/' . $filename;

		if (file_exists($filename) && is_file($filename)) {
			return $filename;
		}

		return null;
	}

}
