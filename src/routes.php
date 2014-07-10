<?php

/**
 * This allows us to route to the correct assets
 */

Route::group(Config::get('smart-assets::routing'), function() {
	Route::get('{path}', Config::get('smart-assets::controller_action'))->where('path', '.*');
});
