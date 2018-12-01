<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeLetter extends Mailable {
	use SerializesModels;

	public $user;
	public $inviter;

	public function __construct(User $user, User $inviter) {
		$this->user = $user;
		$this->inviter = $inviter;
	}

	public function build() {
		return $this->view('emails.welcome');
	}
}
