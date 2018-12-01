<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Hash;
use App\PH\C;
use App\Models\Role;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller {

	public function __construct() {
		$this->middleware('auth')->except(['join', 'complete']);
		$this->middleware('guest')->only(['join', 'complete']);
	}
	
	public function join($token) {
		$user = User::where('registration_token', $token)->firstOrFail();
		return view('members.join', ['user' => $user, 'token' => $token]);
	}
	
	public function complete(Request $request) {
		$this->validate($request, [
			'registration_token' => ['required', 'string', 'min:5', 'exists:users,registration_token'],
			'password' => ['required', 'string', 'min:7', 'confirmed'],
		]);
		
		$user = User::where('registration_token', $request->get('registration_token'))->firstOrFail();
		$user->password = Hash::make($request->get('password'));
		$user->registration_token = null;
		$user->save();
		
		session()->flash('login_email', $user->email);
		session()->flash('message', __('auth.registration_complete'));
		
		return redirect()->route('login');
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
		$this->authorize('add', User::class);
		$roles = $this->getRoles();
		$groups = Group::orderBy('name', 'asc')->get();
		return view('members.create', ['roles' => $roles, 'groups' => $groups]);
	}
	
	public function store(Request $request) {
		$this->authorize('add', User::class);
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
			$token = str_random(64);
			$user = User::create([
				'name' => $request->get('name'),
				'email' => $request->get('email'),
				'role_id' => $request->get('role'),
				'password' => '',
				'registration_token' => $token,
			]);
			
			foreach($request->get('groups') as $group) {
				$user->groups()->attach($group);
			}
			
			event(new \App\Events\NewMemberAdded($user, Auth::user()));
		});
		
		return redirect()->route('members.index');
	}
	
	protected function getRoles() {
		return Role::where('name', '!=', 'superuser')->orderBy('id', 'desc')->get();
	}
}
