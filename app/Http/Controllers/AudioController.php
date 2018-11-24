<?php
namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Bucket;
use Illuminate\Http\Request;

class AudioController extends Controller {
	
	public function __construct() {
		$this->middleware('auth');
	}

	public function show($audio) {
		$record = Audio::findOrFail($audio);
		$this->authorize('view', [Bucket::class, $record->bucket]);
		return view('audio_details', ['audio' => $record]);
	}
	
	public function create(Request $request) {
		$bucket = Bucket::findOrFail($request->get('bucket'));
		$this->authorize('insert', [Bucket::class, $bucket]);
		return view('audio_insert', ['bucket' => $bucket]);
	}

	public function raw($id) {
		$audio = Audio::findOrFail($id);
		$this->authorize('view', [Bucket::class, $audio->bucket]);
		return response()->file(resource_path('audio/' . $audio->path));
	}

	public function download($id) {
		$audio = Audio::findOrFail($id);
		$this->authorize('view', [Bucket::class, $audio->bucket]);
		return response()->download(resource_path('audio/' . $audio->path), $audio->name . 'mp3');
	}
}
