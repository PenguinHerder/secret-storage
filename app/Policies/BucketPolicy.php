<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Bucket;
use Illuminate\Auth\Access\HandlesAuthorization;

class BucketPolicy extends AbstractPolicy {
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }
	
	public function create(User $user) {
		return $this->checkPermission($user, 'buckets.create');
	}
	
	public function before(User $user, $ability) {
		return $this->beforeBone($user, $ability);
	}
}
