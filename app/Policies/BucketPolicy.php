<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use App\Models\Bucket;
use Illuminate\Auth\Access\HandlesAuthorization;

class BucketPolicy extends AbstractPolicy {
    use HandlesAuthorization;
	
	public function view(User $user, Bucket $bucket) {
		$userBuckets = $user->buckets();
		foreach($userBuckets as $userBucket) {
			if($userBucket->id == $bucket->id) {
				return true;
			}
		}
		
		return false;
	}
	
	public function insert(User $user, Bucket $bucket) {
		return $bucket->owner_id == $user->id;
	}
	
	public function create(User $user, Group $group) {
		if($this->checkPermission($user, 'buckets.create')) {
			$userGroup = $user->groups()->wherePivot('group_id', $group->id)->first();
			return $userGroup instanceof Group && $userGroup->id > 0;
		}
		
		return false;
	}
	
	public function update(User $user, Bucket $bucket) {
		return $this->insert($user, $bucket);
	}
	
	public function before(User $user, $ability) {
		return $this->beforeBone($user, $ability);
	}
}
