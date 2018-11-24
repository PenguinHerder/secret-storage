<?php
namespace App\PH\ConfigGenerator;

class Provider {
	protected $config;
	protected $filename = '';
	
	public function __construct(array $config) {
		$this->config = $config;
	}
	
	public function setFilename(string $filename) {
		$this->filename = $filename;
	}
	
	public function getConfigValue(string $key) {
		$ns = $this->filename;
		$split = explode('.', $key);
		$ret = [];
		if(array_key_exists($ns, $this->config) && array_key_exists($split[0], $this->config[$ns])) {
			$ret = $this->config[$ns];
		}
		else {
			$ret = $this->config;
		}
		
		return array_get($ret, $key);
	}

	public function __invoke(string $key) {
		return $this->getConfigValue($key);
	}
}
