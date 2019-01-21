<html lang="en">
	<head></head>
	<body>
		<h3>Welcome to Highden Secret Storage</h3>
		<p>
			You have been invited by {{ $inviter->name }} to join Highden Secret Storage where you can access 
			secret audio recordings.
		</p>
		<p>
			In order to complete your registration and open the vault doors follow 
			<a href="{{ route('join', ['token' => $user->registration_token]) }}">this link</a>.
		</p>
	</body>
</html>
