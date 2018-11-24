<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PH\ConfigGenerator\Provider;
use Illuminate\Filesystem\Filesystem;

class GenerateServerConfig extends Command {

	protected $signature = 'server:config';
	
	protected $description = 'Generates configuration files for nginx, etc';
	
	protected $files;
	protected $provider;
	
	public function __construct(Filesystem $files) {
        parent::__construct();
        $this->files = $files;
		$json = $files->get(resource_path('server/config.json'));
		$this->provider = new Provider(json_decode($json, true));
		app('view')->addNamespace('server', resource_path('server/stubs'));
    }
	
	public function handle() {
		$configFiles = $this->files->files(resource_path('server/stubs'));
		foreach($configFiles as $file) {
			$this->handleFile($file);
		}
	}
	
	protected function handleFile(string $path) {
		$filename = str_replace('.blade.php', '', basename($path));
		$outputName = str_replace('_', '.', $filename);
		$this->provider->setFilename($outputName);
		$config = view('server::'.$filename, ['g' => $this->provider])->render();
		$outputDir = base_path('etc/');
		$this->files->put($outputDir.$outputName, $config);		
	}
}
