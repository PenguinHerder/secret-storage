<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class ExtraController extends Controller {

	public function __construct() {
		
	}

	public function privacy() {
		return view('extra.privacy');
	}
}
