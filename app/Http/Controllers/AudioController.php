<?php
namespace App\Http\Controllers;

use App\PH\C;
use App\Models\Audio;
use App\Models\Bucket;
use Illuminate\Http\Request;
use App\Jobs\ProcessAudioFile;

class AudioController extends Controller {
	
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function index() {
		return redirect()->route('groups.index');
	}

	public function show($audio) {
		$record = Audio::where('id', $audio)->where('status', C::FILE_STATUS_READY)->firstOrFail();
		$this->authorize('view', [Bucket::class, $record->bucket]);
		return view('audio.details', ['audio' => $record]);
	}
	
	public function create(Request $request) {
		$bucket = Bucket::findOrFail($request->get('bucket'));
		$this->authorize('insert', [Bucket::class, $bucket]);
		return view('audio.insert', ['bucket' => $bucket]);
	}
	
	public function store(Request $request) {
		$bucket = Bucket::findOrFail($request->get('bucket_id'));
		$this->authorize('insert', [Bucket::class, $bucket]);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100', 'unique:groups,name'],
			'description' => ['required', 'string', 'between:15,5000'],
			'date_taken' => ['required', 'date_format:Y-m-d'],
			'bucket_id' => ['required', 'exists:buckets,id'],
			'audio' => ['required', 'file', 'mimetypes:audio/x-wav']
		]);
		
		$data = $request->only(['name', 'description', 'date_taken']);
		if($request->file('audio')->isValid()) {
			$audio = $bucket->resources()->create([
				'name' => $data['name'],
				'description' => $data['description'],
				'date_taken' => $data['date_taken'],
				'filename' => str_slug($data['name'], '_') . '_' . str_random(8),
				'duration' => 0,
				'upload_filesize' => $request->file('audio')->getSize(),
				'filesize' => 0,
				'analysis' => '',
				'transcription' => '',
				'status' => C::FILE_STATUS_UPLOADED,
			]);
			
			$request->file('audio')->storeAs('uploads/raw_audio', $audio->filename . '.wav');
			ProcessAudioFile::dispatch($audio);
		}
		
		return redirect()->route('buckets.show', ['bucket' => $bucket->id]);
	}

	public function raw($id) {
		$audio = Audio::findOrFail($id);
		$this->authorize('view', [Bucket::class, $audio->bucket]);
		return response()->file(storage_path('app/uploads/audio/' . $audio->filename . '.mp3'));
	}

	public function download($id) {
		$audio = Audio::findOrFail($id);
		$this->authorize('view', [Bucket::class, $audio->bucket]);
		return response()->download(storage_path('app/uploads/audio/' . $audio->filename . '.mp3'), $audio->name . '.mp3');
	}
}
