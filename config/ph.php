<?php

return [
	'fb_login_id' => env('FB_LOGIN_ID'),
	'fb_secret_app' => env('FB_SECRET'),
	
	'commands' => [
		'default' => env('COMMAND_MODE', 'gentoo'),
		
		'gentoo' => [
			'sox' => '/usr/bin/sox',
			'lame' => '/usr/bin/lame',
		],
	]
];
