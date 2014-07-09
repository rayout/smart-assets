<?php namespace Kodeks\SmartAssets;

class AssetPipeline
{

	/**
	 * Parser
	 *
	 * @var FileParser
	 */
	private $parser;

	/**
	 * Generator
	 * @var FileGenerator
	 */
	private $generator;

	/**
	 * Create the asset repository based on this setup
	 *
	 * @param unsure atm...
	 */
	public function __construct($parser, $generator)
	{
		$this->parser = $parser;
		$this->generator = $generator;
	}

	/**
	 * Is this asset a javascript type?
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public function isJavascript($filename)
	{
		return $this->parser->absolutePath($filename, 'javascripts');
	}

	/**
	 * Is this filename a stylesheet type?
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public function isStylesheet($filename)
	{
		return $this->parser->absolutePath($filename, 'stylesheets');
	}

	/**
	 * Get the config array
	 *
	 * @return array
	 */
	public function getConfig()
	{
		return $this->parser->config;
	}

	/**
	 * Return the javascript associated with this path
	 *
	 * @param string $path
	 * @return string
	 */
	public function javascript($absolutePath)
	{
		return $this->generator->javascript($absolutePath);
	}

	/**
	 * Return the stylesheet associated with this path
	 *
	 * @param string $absolutePath
	 * @return string
	 */
	public function stylesheet($absolutePath)
	{
		return $this->generator->stylesheet($absolutePath);
	}

	public function getParser()
	{
		return $this->parser;
	}

}
