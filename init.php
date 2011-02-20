<?php

Route::set(
	'resizer',
	'thumb(/<path>)/<type>_<x>x<y>/<name>.<ext>',
	array(
		'path' => '.*',
		'type' => '(res|crop|cropr)',
		'x'    => '[\d]{1,3}',
		'y'    => '[\d]{1,3}',
		'name' => '[0-9a-f]{32}',
		'ext'  => '(jpe?g|png|gif)',
	)
)->defaults(array(
	'controller' => 'resizer',
	'action'     => 'index',
));

