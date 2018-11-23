<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function details($id)
    {
		$audio = Audio::findOrFail($id);
        return view('audio_details', ['audio' => $audio]);
    }
	
    public function raw($id)
    {
		$audio = Audio::findOrFail($id);
        return response()->file(resource_path('audio/' . $audio->path));
    }
	
    public function download($id)
    {
		$audio = Audio::findOrFail($id);
        return response()->download(resource_path('audio/' . $audio->path), $audio->name . 'mp3');
    }
}
