<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email',
    ];
	
	public function role() {
		return $this->belongsTo(Role::class);
	}
	
	public function groups() {
		return $this->belongsToMany(Group::class, 'users_groups');
	}
	
	public function buckets() {
		$buckets = [];
		foreach($this->groups()->with('buckets')->get() as $group) {
			foreach($group->buckets as $bucket) {
				$buckets[] = $bucket;
			}
		}
		
		return collect($buckets);
	}
}
