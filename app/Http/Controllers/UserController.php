<?php
namespace App\Http\Controllers;

use Auth;
use App\PH\C;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$this->authorize('view', User::class);
		
		$users = User::orderBy('name', 'asc')->with(['role', 'groups'])->get();
		return view('user_list', ['users' => $users]);
	}

	public function details($id) {
		$bucket = Bucket::findOrFail($id);
		switch($bucket->type) {
			case C::BUCKET_TYPE_AUDIO: $view = 'audio_bucket'; break;
		}

		return view($view, ['bucket' => $bucket]);
	}
}
