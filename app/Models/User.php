<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'role_id',
    ];
	
	public function role() {
		return $this->belongsTo(Role::class);
	}
	
	public function groups() {
		return $this->belongsToMany(Group::class, 'users_groups');
	}
	
	public function buckets() {
		$buckets = [];
		$ids = [];
		foreach($this->groups()->with('buckets')->get() as $group) {
			foreach($group->buckets as $bucket) {
				if(!in_array($bucket->id, $ids)) {
					$buckets[] = $bucket;
					$ids[] = $bucket->id;
				}
			}
		}
		
		return collect($buckets);
	}
}
