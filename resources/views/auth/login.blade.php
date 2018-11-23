@extends('layouts.app')

@section('content')
@unless($errors->has('access_token'))
<script>
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '{{ config("ph.fb_login_id") }}',
			cookie     : true,
			xfbml      : true,
			version    : 'v3.2'
		});

		FB.AppEvents.logPageView();  

		FB.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	};


	function statusChangeCallback(response) {
		if(response.status === 'connected') {
			document.forms[0].access_token.value = response.authResponse.accessToken;
			document.forms[0].submit();
		}
		else {
			FB.login();
		}
	}

	function checkLoginState() {
		FB.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	 }

	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	 }(document, 'script', 'facebook-jssdk'));
	 
</script>
@endunless
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>
				@if($errors->has('access_token'))
					<div class="col-md-6">
						<span class="invalid-feedback d-block" role="alert">
							<strong>{{ $errors->first('access_token') }}</strong>
						</span>
					</div>
				@else
					<div class="valid-feedback d-block">
						Facebook automatic login in progress
					</div>
					<div class="card-body">
						<fb:login-button 
						  scope="public_profile,email"
						  onlogin="checkLoginState();">
						</fb:login-button>
					</div>
				@endif
            </div>
        </div>
    </div>
	<form method="POST" action="{{ route('login') }}">
		@csrf

		<div class="col-md-6">
			<input name="access_token" type="hidden" id="access_token" value="">
		</div>
	</form>

</div>
@endsection
