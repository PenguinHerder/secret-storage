@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		Create a new bucket
	</h4>
	
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('buckets.store') }}">
				@csrf
				
				<input type="hidden" name="group_id" value='{{ $group->id }}'>

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
					<label for="description" class="col-sm-4 col-form-label text-md-right">Description</label>

					<div class="col-md-6">
						<textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description">{{ old('description') }}</textarea>

						@if ($errors->has('description'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
						@endif
					</div>
				</div>

				<div class="form-group row">
					<label for="type" class="col-sm-4 col-form-label text-md-right">Type</label>

					<div class="col-md-6">
						<select id="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type">
							<option value=''></option>
							<option value='{{ App\PH\C::BUCKET_TYPE_AUDIO }}'>Audio bucket</option>
						</select>

						@if ($errors->has('type'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('type') }}</strong>
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
