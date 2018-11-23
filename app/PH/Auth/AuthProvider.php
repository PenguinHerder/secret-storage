<?php
namespace App\PH\Auth;

use App\Models\User;
use Facebook\Facebook;
use Illuminate\Contracts\Auth\UserProvider;

class AuthProvider implements UserProvider {
	
	public function retrieveByCredentials(array $credentials) {
		$data = $this->getFacebookData($credentials['access_token']);
		if($data) {
			$user = User::where('email', $data->getEmail())->first();
			if($user) {
				$user->update(['name' => $data->getName()]);
			}
			
			return $user;
		}
		
		return null;
	}

	public function retrieveById($identifier) {
		return User::find($identifier);
	}

	public function retrieveByToken($identifier, $token) {
		return null; // not implemented
	}

	public function updateRememberToken(\Illuminate\Contracts\Auth\Authenticatable $user, $token): void {
		// not implemented
	}

	public function validateCredentials(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials): bool {
		return true;
	}
	
	protected function getFacebookData(string $accessToken) {
		$config = config('ph');
		
		$fb = new Facebook([
			'app_id' => $config['fb_login_id'],
			'app_secret' => $config['fb_secret_app'],
			'default_graph_version' => 'v3.2',
			'default_access_token' => $accessToken,
		]);

		try {
			$response = $fb->get('/me?fields=id,name,email');
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			return null;
		}

		$me = $response->getGraphUser();
		
		return $me;
	}
}
