<?php
namespace App\Http\Controllers;

use App\PH\C;
use App\Models\Bucket;
use Illuminate\Http\Request;

class BucketController extends Controller {
	
    public function __construct() {
        $this->middleware('auth');
    }

    public function details($id) {
		$bucket = Bucket::findOrFail($id);
		switch($bucket->type) {
			case C::BUCKET_TYPE_AUDIO: $view = 'audio_bucket'; break;
		}
		
        return view($view, ['bucket' => $bucket]);
    }
}
