<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model {

    protected $fillable = [
        'user_id', 'sections', 'approved',
    ];
	
	protected $casts = [
		'sections' => 'array',
	];
	
	public function audio() {
		return $this->belongsTo(Audio::class);
	}
	
	public function author() {
		return $this->belongsTo(User::class);
	}
}
