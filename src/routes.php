<?php

/**
 * This allows us to route to the correct assets
 */

Route::group(Config::get('smart-assets::routing'), function() {
	Route::get('{path}', Config::get('smart-assets::controller_action'))->where('path', '.*');
});

App::missing(function($exception)
{
	return Response::view('smart-assets::404', array(), 404);
});