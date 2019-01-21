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
//		$buckets = Bucket::orderBy('created_at', 'desc')->get();
		$buckets = $user->buckets();
		
		$all = null;
		if($user->can('spy', Group::class)) {
			$ids = $buckets->pluck('id')->all();
			$all = Bucket::whereNotIn('id', $ids)->get();
		}
		
		return view('bucket.list', ['buckets' => $buckets, 'all' => $all]);
	}

	public function show($id) {
		$bucket = Bucket::findOrFail($id);
		switch($bucket->type) {
			case C::BUCKET_TYPE_AUDIO: $view = 'bucket.audio'; break;
		}

		return view($view, ['bucket' => $bucket]);
	}
	
	public function create(Request $request) {
		$group = Group::findOrFail($request->get('group'));
		$this->authorize('create', [Bucket::class, $group]);
		return view('bucket.create', ['group' => $group]);
	}
	
	public function store(Request $request) {
		$group = Group::findOrFail($request->get('group_id'));
		$this->authorize('create', [Bucket::class, $group]);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100'],
			'description' => ['required', 'string', 'between:15,5000'],
			'type' => \Illuminate\Validation\Rule::in([C::BUCKET_TYPE_AUDIO,]),
			'group_id' => ['required', 'exists:groups,id'],
		]);
		
		$user = Auth::user();
		$create = array_merge($request->only(['name', 'description', 'type']), ['owner_id' => $user->id]);
		$group->buckets()->create($create);
		
		return redirect()->route('groups.show', ['group' => $group->id]);
	}
}
