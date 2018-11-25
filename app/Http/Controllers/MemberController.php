<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use App\PH\C;
use App\Models\Role;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$this->authorize('view', User::class);
		
		$users = User::orderBy('name', 'asc')->with(['role', 'groups'])->get();
		return view('members.list', ['users' => $users]);
	}

	public function details($id) {
		$bucket = Bucket::findOrFail($id);
		switch($bucket->type) {
			case C::BUCKET_TYPE_AUDIO: $view = 'audio_bucket'; break;
		}

		return view($view, ['bucket' => $bucket]);
	}

	public function create() {
		$this->authorize('create', User::class);
		$roles = $this->getRoles();
		$groups = Group::orderBy('name', 'asc')->get();
		return view('members.create', ['roles' => $roles, 'groups' => $groups]);
	}
	
	public function store(Request $request) {
		$this->authorize('create', User::class);
		$roles = $this->getRoles();
		$groups = Group::all();
		
		$this->validate($request, [
			'name' => ['required', 'string', 'between:5,100'],
			'email' => ['required', 'string', 'email', 'unique:users,email'],
			'role' => Rule::in($roles->pluck('id')),
			'groups' => ['required', 'array', 'min:1'],
			'groups.*' => Rule::in($groups->pluck('id'))
		]);
		
		DB::transaction(function() use($request) {
			$fields = $request->only(['name', 'email', 'role']);
			$user = User::create(['name' => $fields['name'], 'email' => $fields['email'], 'role_id' => $fields['role']]);
			foreach($request->get('groups') as $group) {
				$user->groups()->attach($group);
			}
		});
		
		return redirect()->route('members.index');
	}
	
	protected function getRoles() {
		return Role::where('name', '!=', 'superuser')->orderBy('id', 'desc')->get();
	}
}
