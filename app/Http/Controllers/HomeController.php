<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$user = Auth::user();
		$buckets = $user->buckets();
//		$buckets = Bucket::orderBy('created_at', 'desc')->get();
        return view('bucket_list', ['buckets' => $buckets]);
    }
}
