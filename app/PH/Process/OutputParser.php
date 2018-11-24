<?php
namespace App\PH\Process;

class OutputParser {
	
	protected $parsers = [];
	
	public function parse(string $class, string $data) {
		return $this->getParser($class)->parse($data);
	}
	
	protected function getParser(string $class) {
		if(!array_key_exists($class, $this->parsers)) {
			$name = __NAMESPACE__ . '\\Parsers\\' . $class;
			$this->parsers[$class] = new $name();
		}
		
		return $this->parsers[$class];
	}
}
