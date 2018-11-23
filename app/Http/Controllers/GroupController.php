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
		
		return view('group_list', ['groups' => $groups, 'all' => $all]);
	}

	public function show($group) {
		$group = Group::findOrFail($group);

		return view('group_details', ['group' => $group]);
	}
	
	public function create() {
		$this->authorize('create', Group::class);
		return view('group_create');
	}
	
	public function store(Request $request) {
		$this->authorize('create', Group::class);
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100', 'unique:groups,name']
		]);
		
		Group::create(['name' => $request->get('name'), 'owner_id' => Auth::user()->id]);
		return redirect()->route('groups.index');
	}
}
