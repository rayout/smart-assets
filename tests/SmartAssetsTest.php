<?php namespace Kodeks\SmartAssets;

use Kodeks\SmartAssets\File\FileParser;
use Kodeks\SmartAssets\File\FileGenerator;


class SmartAssetsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $base = __DIR__ . '/fixtures';

        $config = include __DIR__ . '/../src/config/config.php';
        $config['base_path'] = $base;
        $config['env'] = "local";
        $config['paths'] = array("app/assets");

        $parser = new FileParser($config);
        $generator = new FileGenerator($config);

        $this->base = $base;
        $this->config = $config;
        $this->pipeline = new AssetPipeline($parser, $generator);
    }

    public function testIsJavascript()
    {
        $this->assertNotNull($this->pipeline->isJavascript('/javascripts/application.js'));
        $this->assertNull($this->pipeline->isJavascript('/javascripts/some.swf'));
        $this->assertNull($this->pipeline->isJavascript('/stylesheets/application.css'));
    }

    public function testIsStylesheet()
    {
        $this->assertNull($this->pipeline->isStylesheet('/javascripts/application.js'));
        $this->assertNull($this->pipeline->isStylesheet('/javascripts/some.swf'));
        $this->assertNotNull($this->pipeline->isStylesheet('/stylesheets/application.css'));
    }


	public function testJavascript()
	{
		$output = $this->pipeline->javascript("{$this->base}/app/assets/javascripts/application.js");
		$this->assertNotEmpty($output);
	}

	public function testStylesheet()
	{
		$output = $this->pipeline->stylesheet("{$this->base}/app/assets/stylesheets/application.css");
		$this->assertNotEmpty($output);

	}

}
