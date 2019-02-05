<?php
namespace App\PH\Process\Parsers;

use App\PH\C;

class GetFileMimeType {
	
	public function parse(string $data) {
		$mime = $this->getMime($data);
		
		switch($mime) {
			case 'audio/x-wav': return C::UPLOAD_AUDIO_TYPE_WAV;
			case 'audio/mpeg': return C::UPLOAD_AUDIO_TYPE_MP3;
				
			default: return C::UPLOAD_AUDIO_TYPE_WAV;
		}
	}
	
	protected function getMime(string $data) {
		preg_match("/^(?<mime>[^;]+);/", $data, $matches);
		return $matches['mime'] ?? null;
	}
}
