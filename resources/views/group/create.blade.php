@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		Create a new group
	</div>
	
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('groups.store') }}">
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
