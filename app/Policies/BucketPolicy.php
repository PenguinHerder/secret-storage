<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use App\Models\Bucket;
use Illuminate\Auth\Access\HandlesAuthorization;

class BucketPolicy extends AbstractPolicy {
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }
	
	public function create(User $user, $groupId) {
		if($this->checkPermission($user, 'buckets.create')) {
			$group = $user->groups()->wherePivot('group_id', $groupId)->first();
			return $group instanceof Group && $group->id > 0;
		}
		
		return false;
	}
	
	public function before(User $user, $ability) {
		return $this->beforeBone($user, $ability);
	}
}
