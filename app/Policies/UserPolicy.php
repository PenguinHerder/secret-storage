<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends AbstractPolicy {
    use HandlesAuthorization;
	
	public function view(User $user) {
		return $this->checkPermission($user, 'users.view');
	}
	
	public function create(User $user) {
		return $this->checkPermission($user, 'users.create');
	}
	
	public function promote(User $user) {
		return $this->checkPermission($user, 'users.promote');
	}
	
	public function before(User $user, $ability) {
		return $this->beforeBone($user, $ability);
	}
}
