<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy extends AbstractPolicy {
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
