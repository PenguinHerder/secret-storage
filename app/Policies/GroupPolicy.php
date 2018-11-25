<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy extends AbstractPolicy {
    use HandlesAuthorization;
	
	public function view(User $user, Group $group) {
		$userCount = $user->groups()->wherePivot('group_id', $group->id)->count();
		return $userCount == 1;
	}
	
	public function create(User $user) {
		return $this->checkPermission($user, 'groups.create');
	}
	
	public function spy(User $user) {
		return $this->checkPermission($user, 'groups.spy');
	}
	
	public function before(User $user, $ability) {
		return $this->beforeBone($user, $ability);
	}
}
