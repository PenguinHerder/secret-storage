<?php
namespace App\Http\Controllers;

use Auth;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$user = Auth::user();
		$groups = $user->groups;
		
		$all = null;
		if($user->can('spy', Group::class)) {
			$ids = $groups->pluck('id')->all();
			$all = Group::whereNotIn('id', $ids)->get();
		}
		
		return view('group.list', ['groups' => $groups, 'all' => $all]);
	}

	public function show($group) {
		$model = Group::findOrFail($group);
		$this->authorize('view', $model);
		return view('group.details', ['group' => $model]);
	}
	
	public function create() {
		$this->authorize('create', Group::class);
		return view('group.create');
	}
	
	public function store(Request $request) {
		$this->authorize('create', Group::class);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:3,100', 'unique:groups,name']
		]);
		
		$user = Auth::user();
		$group = Group::create(['name' => $request->get('name'), 'owner_id' => $user->id]);
		$group->members()->attach($user);
		
		return redirect()->route('groups.index');
	}
}
