<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model {

    protected $fillable = [
        'name', 'description', 'date_taken', 'path',
    ];
	
	public function bucket() {
		return $this->belongsTo(Bucket::class);
	}
}
