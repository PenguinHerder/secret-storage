<?php
namespace App\PH;

class CommandFinder {
	
	protected static $config;
	
	public static function find(string $command) {
		$config = self::getConfig();
		return array_get($config, $command, $command);
	}
	
	public static function replace(string $command) {
		return preg_replace_callback("/^\w+/", function(array $matches) {
			return self::find($matches[0]);
		}, $command);
	}
	
	protected static function getConfig() {
		if(self::$config === null) {
			$key = config('ph.commands.default');
			self::$config = config('ph.commands.' . $key);
		}
		
		return self::$config;
	}
}
