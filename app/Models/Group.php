<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    protected $fillable = [
        'name', 'owner_id',
    ];
	
	public function users() {
		return $this->belongsToMany(User::class, 'users_groups');
	}
	
	public function buckets() {
		return $this->belongsToMany(Bucket::class, 'groups_buckets');
	}
	
	public function owner() {
		return $this->belongsTo(User::class, 'owner_id');
	}
}
