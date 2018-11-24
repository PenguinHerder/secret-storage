<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model {

    protected $fillable = [
        'name', 'description', 'date_taken', 'filename', 'duration',
		'upload_filesize', 'filesize', 'analysis', 'transcription', 'status',
    ];
	
	public function bucket() {
		return $this->belongsTo(Bucket::class);
	}
}
