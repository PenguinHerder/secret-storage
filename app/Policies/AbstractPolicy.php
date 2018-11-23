<?php
namespace App\Policies;

use App\Models\User;

abstract class AbstractPolicy {
	
	protected function checkPermission(User $user, string $key) {
		$perms = $user->role->permissions;
		return array_get($perms, $key, false);
	}
	
	protected function beforeBone(User $user, $ability) {
		$perms = $user->role->permissions;
		return array_get($perms, 'can_see_everything', false) === true ? true : null;
	}
}
