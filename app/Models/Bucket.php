<?php
namespace App\Models;

use App\PH\C;
use Illuminate\Database\Eloquent\Model;

class Bucket extends Model {

    protected $fillable = [
        'name', 'description', 'type', 'owner_id',
    ];
	
	public function resources() {
		switch($this->type) {
			case C::BUCKET_TYPE_AUDIO: return $this->hasMany(Audio::class);
		}
	}
	
	public function groups() {
		return $this->belongsToMany(Group::class, 'groups_buckets');
	}
	
	public function owner() {
		return $this->belongsTo(User::class, 'owner_id');
	}
}
