<?php
namespace App\Http\Controllers;

use Auth;
use App\PH\C;
use App\Models\Bucket;
use Illuminate\Http\Request;

class BucketController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$user = Auth::user();
		$buckets = $user->buckets();
	//		$buckets = Bucket::orderBy('created_at', 'desc')->get();
		return view('bucket_list', ['buckets' => $buckets]);
	}

	public function details($id) {
		$bucket = Bucket::findOrFail($id);
		switch($bucket->type) {
			case C::BUCKET_TYPE_AUDIO: $view = 'audio_bucket'; break;
		}

		return view($view, ['bucket' => $bucket]);
	}
}
