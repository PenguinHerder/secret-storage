<?php
namespace App\Http\Controllers;

use Auth;
use App\PH\C;
use App\Models\Audio;
use App\Models\Bucket;
use App\Models\Analysis;
use App\PH\Process\RunTrait;
use Illuminate\Http\Request;
use App\Jobs\ProcessAudioFile;

class AudioController extends Controller {
	use RunTrait;
	
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function index() {
		return redirect()->route('groups.index');
	}

	public function show($audio) {
		$model = Audio::where('id', $audio)->where('status', C::FILE_STATUS_READY)->firstOrFail();
		$this->authorize('view', [Bucket::class, $model->bucket]);
		$model->load([
			'analyses' => function($query) use($model) {
				$userId = Auth::user()->id;
				if($model->bucket->owner_id != $userId) {
					$query->where('approved', true)->orWhere('user_id', $userId);
				}
			},
			'analyses.author'
		]);
		
		return view('audio.details', ['audio' => $model]);
	}
	
	public function create(Request $request) {
		$bucket = Bucket::findOrFail($request->get('bucket'));
		$this->authorize('insert', [Bucket::class, $bucket]);
		return view('audio.insert', ['bucket' => $bucket, 'audio' => null]);
	}
	
	public function edit($audio) {
		$audioModel = Audio::findOrFail($audio);
		$this->authorize('insert', [Bucket::class, $audioModel->bucket]);
		return view('audio.insert', ['bucket' => $audioModel->bucket, 'audio' => $audioModel]);
	}
	
	public function update($audio, Request $request) {
		$audioModel = Audio::findOrFail($audio);
		$this->authorize('update', [Bucket::class, $audioModel->bucket]);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100'],
			'description' => ['required', 'string', 'between:10,5000'],
			'date_taken' => ['required', 'date_format:Y-m-d'],
		]);
		
		$audioModel->name = $request->get('name');
		$audioModel->description = $request->get('description');
		$audioModel->date_taken = $request->get('date_taken');
		$audioModel->save();
		return redirect()->route('audios.show', ['audio' => $audioModel->id]);
	}
	
	public function store(Request $request) {
		$bucket = Bucket::findOrFail($request->get('bucket_id'));
		$this->authorize('insert', [Bucket::class, $bucket]);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100'],
			'description' => ['required', 'string', 'between:10,5000'],
			'date_taken' => ['required', 'date_format:Y-m-d'],
			'bucket_id' => ['required', 'exists:buckets,id'],
			'audio' => ['required', 'file', 'mimetypes:audio/x-wav,audio/mpeg,audio/mp3']
		]);
		
		$data = $request->only(['name', 'description', 'date_taken']);
		if($request->file('audio')->isValid()) {
			$type = $this->execute('file -b --mime ' . $request->file('audio')->getPathname(), 'GetFileMimeType')['data'];
			$audio = $bucket->resources()->create([
				'name' => $data['name'],
				'description' => $data['description'],
				'date_taken' => $data['date_taken'],
				'filename' => str_slug(substr($data['name'], 0, 23), '_') . '_' . str_random(8),
				'duration' => 0,
				'upload_type' => $type,
				'upload_filesize' => $request->file('audio')->getSize(),
				'filesize' => 0,
				'analysis' => '',
				'transcription' => '',
				'status' => C::FILE_STATUS_UPLOADED,
			]);
			
			$ext = $type == C::UPLOAD_AUDIO_TYPE_WAV ? '.wav' : '.mp3';
			$request->file('audio')->storeAs('uploads/raw_audio', $audio->filename . $ext);
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
	
	public function saveAnalysis($audio, Request $request) {
		$model = Audio::where('id', $audio)->where('status', C::FILE_STATUS_READY)->firstOrFail();
		$this->authorize('view', [Bucket::class, $model->bucket]);
		
		$this->validate($request, [
			'data' => ['required', 'json'],
		]);
		
		$data = json_decode($request->get('data'), true);
		$this->getValidationFactory()->make($data, [
				'*.start' => ['required', 'int'],
				'*.end' => ['required', 'int'],
				'*.content' => ['required', 'min:3'],
			]
        )->validate();
		
		$this->analysisToDB($model, $data);
		
		return response()->json([
			'status' => 'success',
			'analyses' => $model->analyses()->with('author')->get(),
		]);
	}
	
	public function approveAnalysis($audio, Request $request) {
		$model = Audio::where('id', $audio)->where('status', C::FILE_STATUS_READY)->firstOrFail();
		$this->authorize('insert', [Bucket::class, $model->bucket]);
		
		$this->validate($request, [
			'id' => ['required', 'int', 'exists:analyses,id'],
		]);
		
		$analysis = Analysis::find($request->get('id'));
		$analysis->approved = true;
		$analysis->save();
		
		return response()->json([
			'status' => 'success',
			'analyses' => $model->analyses()->with('author')->get(),
		]);
		
	}
	
	protected function analysisToDB(Audio $audio, array $data) {
		$exists = $audio->analyses()->where('user_id', Auth::user()->id)->first();
		if($exists) {
			$exists->sections = $data;
			$exists->approved = true;
			$exists->save();
		}
		else {
			$audio->analyses()->create([
				'user_id' => Auth::user()->id,
				'sections' => $data,
				'approved' => true,
			]);
		}
	}
}
