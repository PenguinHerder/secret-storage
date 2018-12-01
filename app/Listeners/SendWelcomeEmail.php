<?php
namespace App\Listeners;

use Mail;
use App\Mail\WelcomeLetter;
use App\Events\NewMemberAdded;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail implements ShouldQueue {

    public function handle(NewMemberAdded $e) {
		$mail = new WelcomeLetter($e->user, $e->inviter);
        Mail::to($e->user->email)->send($mail);
    }
}
