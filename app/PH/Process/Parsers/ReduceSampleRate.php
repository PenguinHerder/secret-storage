<?php
namespace App\PH\Process\Parsers;

class ReduceSampleRate {
	
	public function parse(string $data) {
		return [
			'duration' => $this->parseDuration($data),
		];
	}
	
	protected function parseDuration(string $data) {
		preg_match("/Output File.*Duration *: *(?<duration>[\d:]+)/is", $data, $matches);
		return $matches['duration'] ?? null;
	}
}
