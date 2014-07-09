<?php namespace Kodeks\SmartAssets\Filters;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

class JSMinPlusFilter implements FilterInterface
{
    public function filterLoad(AssetInterface $asset)
    {
	    exit();
    }

    public function filterDump(AssetInterface $asset)
    {exit();
		$asset->setContent(\JSMinPlus::minify($asset->getContent()) . ';');
    }
}
