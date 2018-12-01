<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class NewMemberAdded {
    use SerializesModels;

	public $user;
	public $inviter;
	
    public function __construct(User $user, User $inviter) {
        $this->user = $user;
		$this->inviter = $inviter;
    }
}
