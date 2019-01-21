<?php
namespace App\Jobs;

use App\PH\C;
use Exception;
use App\Models\Audio;
use App\PH\Process\RunTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessAudioFile implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RunTrait;

	protected $audio;
	protected $fs;
	protected $tmp;

	protected $input;
	protected $output;

	public function __construct(Audio $audio) {
		$this->audio = $audio;
	}
	
	public function __destruct() {
		if($this->tmp && $this->fs->exists($this->tmp)) {
			$this->fs->deleteDirectory($this->tmp);
		}
	}

	public function handle() {
		$this->initialize();
		$this->createTmpFolder();
		$data = $this->reduceSampleRate();
		$this->updateDuration($data);
		$this->reduceNoise();
		$this->compress();
		$this->finalize();
	}
	
	protected function finalize() {
		$file = $this->output . '/' . $this->audio->filename . '.mp3';
		$size = $this->fs->size($file);
		$this->audio->filesize = $size;
		$this->audio->status = C::FILE_STATUS_READY;
		$this->audio->save();
		
		$this->fs->delete($this->input . '/' . $this->audio->filename . '.wav');
	}
	
	protected function compress() {
		$input = $this->tmp . '/sample_rate_reduce.wav';
		$output = $this->output . '/' . $this->audio->filename . '.mp3';
		$cmd = "lame -b 96 -h {$input} {$output}";
		$this->execute($cmd, null, true, 360);
	}
	
	protected function reduceNoise() {
		$input = $this->tmp . '/sample_rate_reduce.wav';
		$output = $this->tmp . '/reduced_noise.wav';
		$profile = $this->tmp . '/noise_profile';
		$acquireCommand = "sox {$input} -n trim 1 2 noiseprof {$profile}";
		$this->execute($acquireCommand, null, true, 60);
		
		$reduceCommand = "sox {$input} {$output} noisered {$profile} 0.33";
		$this->execute($reduceCommand, null, true, 240);
	}
	
	protected function updateDuration(array $data) {
		$duration = $data['data']['duration'];
		preg_match("/^(?<hours>\d+):(?<minutes>\d+):(?<seconds>\d+)$/", $duration, $matches);
		$parsed = (int)$matches['hours'] * 3600 + (int)$matches['minutes'] * 60 + (int)$matches['seconds']; 
		$this->audio->duration = $parsed;
	}
	
	protected function reduceSampleRate() {
		$input = $this->input . '/' . $this->audio->filename . '.wav';
		$output = $this->tmp . '/sample_rate_reduce.wav';
		$cmd = "sox -V3 {$input} -r 22500 -c 1 {$output}";
		return $this->execute($cmd, 'ReduceSampleRate', true, 240);
	}
	
	protected function createTmpFolder() {
		$folder = sys_get_temp_dir() . '/' . str_random(8);
		if($this->fs->exists($folder)) {
			throw new Exception("Path already exists");
		}
		
		if(!$this->fs->makeDirectory($folder)) {
			throw new Exception("Temporary folder could not be created!");
		}
		
		$this->tmp = $folder;
	}
	
	protected function initialize() {
		$this->fs = new Filesystem();
		$this->input = storage_path('app/uploads/raw_audio');
		$this->output = storage_path('app/uploads/audio');
		
		foreach([$this->input, $this->output] as $folder) {
			if(!$this->fs->exists($folder)) {
				$this->fs->makeDirectory($folder);
			}
		}
	}
}
