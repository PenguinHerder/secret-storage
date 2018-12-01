<?php

return [
	'commands' => [
		'default' => env('COMMAND_MODE', 'gentoo'),
		
		'gentoo' => [
			'sox' => '/usr/bin/sox',
			'lame' => '/usr/bin/lame',
		],
	]
];
