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
		return view('group_list', ['groups' => $groups]);
	}

	public function details($id) {
		$group = Group::findOrFail($id);

		return view('group_details', ['group' => $group]);
	}
}
