<?php
namespace App\PH\Process;

use App\PH\CommandFinder;
use Symfony\Component\Process\Process;

trait RunTrait {
	
	protected $parser;
	
	protected function execute(string $command, string $parser = null, bool $mustRun = true) {
		$run = CommandFinder::replace($command);
		$process = new Process($run);
		$mustRun ? $process->mustRun() : $process->run();
		
		$data = [
			'success' => $process->getExitCode() === 0,
		];
		
		if($parser !== null) {
			$output = $process->getOutput() . "\n\n" . $process->getErrorOutput();
			$data['data'] = $this->getParser()->parse($parser, $output);
		}
		
		return $data;
	}
	
	protected function getParser() {
		if($this->parser === null) {
			$this->parser = new OutputParser();
		}
		
		return $this->parser;
	}
}
