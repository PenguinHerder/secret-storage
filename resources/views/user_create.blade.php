@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		Add a new member
	</div>
	
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('users.store') }}">
				@csrf

				<div class="form-group row">
					<label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>

					<div class="col-md-6">
						<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

						@if ($errors->has('name'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('name') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-sm-4 col-form-label text-md-right">Email</label>

					<div class="col-md-6">
						<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

						@if ($errors->has('email'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group row">
					<label for="role" class="col-sm-4 col-form-label text-md-right">Role</label>

					<div class="col-md-6">
						<select id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role">
							<option value=''></option>
							@foreach($roles as $role)
								<option value='{{ $role->id }}' {{ $role->name === 'default' ? 'selected="selected"' : ''}}>{{ $role->name }}</option>
							@endforeach
						</select>

						@if ($errors->has('role'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('role') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group row">
					<label for="groups" class="col-sm-4 col-form-label text-md-right">Groups</label>

					<div class="col-md-6">
						<select id="groups" class="form-control{{ $errors->has('groups') ? ' is-invalid' : '' }}" name="groups[]" multiple="multiple" size='8'>
							@foreach($groups as $group)
								<option value='{{ $group->id }}'>{{ $group->name }}</option>
							@endforeach
						</select>

						@if ($errors->has('groups'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('groups') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group row mb-0">
					<div class="col-md-8 offset-md-4">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</form>
        </div>
    </div>
</div>
@endsection
