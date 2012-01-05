<?php

Route::set(
	'resizer',
	'thumbnails(/<path>)/<type>_<x>x<y>/<name>.<ext>',
	array(
		'path' => '.*',
		'type' => '(res|crop|cropr|cropg)',
		'x'    => '[\d]{1,3}',
		'y'    => '[\d]{1,3}',
		// 'name' => '[0-9a-f]{32}',
		'name' => '[^/]+',
		'ext'  => '((j|J)(p|P)(e|E)?(g|G)|(p|P)(n|N)(g|G)|(g|G)(i|I)(f|F))',
	)
)->defaults(array(
	'controller' => 'resizer',
	'action'     => 'index',
));
