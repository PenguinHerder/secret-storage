<?php
namespace App\Http\Controllers;

use Auth;
use App\PH\C;
use App\Models\Group;
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

	public function show($id) {
		$bucket = Bucket::findOrFail($id);
		switch($bucket->type) {
			case C::BUCKET_TYPE_AUDIO: $view = 'audio_bucket'; break;
		}

		return view($view, ['bucket' => $bucket]);
	}
	
	public function create(Request $request) {
		$groupId = $request->get('group');
		$this->authorize('create', [Bucket::class, $groupId]);
		return view('bucket_create', ['group_id' => $groupId]);
	}
	
	public function store(Request $request) {
		$groupId = $request->get('group_id');
		$this->authorize('create', [Bucket::class, $groupId]);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100', 'unique:groups,name'],
			'description' => ['required', 'string', 'between:20,5000'],
			'type' => \Illuminate\Validation\Rule::in([C::BUCKET_TYPE_AUDIO,]),
			'group_id' => ['required', 'exists:groups,id'],
		]);
		
		$user = Auth::user();
		$create = array_merge($request->only(['name', 'description', 'type']), ['owner_id' => $user->id]);
		$group = Group::find($groupId);
		$group->buckets()->create($create);
		
		return redirect()->route('groups.show', ['group' => $groupId]);
	}
}
