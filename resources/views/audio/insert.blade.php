@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		Add a new audio file to the "{{ $bucket->name }}" bucket
	</h4>
	
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ $audio ? route('audios.update', $audio->id) : route('audios.store') }}" enctype="multipart/form-data">
				@csrf
				
				<input type="hidden" name="bucket_id" value='{{ $bucket->id }}'>
				@if($audio)
					@method('PUT')
				@endif

				<div class="form-group row">
					<label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>

					<div class="col-md-6">
						<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ?? $audio->name ?? '' }}" required autofocus>

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
						<textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description">{{ old('description') ?? $audio->description ?? '' }}</textarea>

						@if ($errors->has('description'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
						@endif
					</div>
				</div>
									
				<div class="form-group row">
					<label for="date_taken" class="col-sm-4 col-form-label text-md-right">Date Taken</label>

					<div class="col-md-6">
						<input id="date_taken" type="date" class="form-control{{ $errors->has('date_taken') ? ' is-invalid' : '' }}" name="date_taken" value="{{ old('date_taken') ?? $audio->date_taken ?? '' }}" required>

						@if ($errors->has('date_taken'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('date_taken') }}</strong>
							</span>
						@endif
					</div>
				</div>

				@unless($audio)
					<div class="form-group row">
						<label for="audio" class="col-sm-4 col-form-label text-md-right">File (max. {{ bytesToHuman(getMaximumFileUploadSize()) }})</label>

						<div class="col-md-6">
							<input id="audio" type="file" class="form-control{{ $errors->has('audio') ? ' is-invalid' : '' }}" name="audio" accept="audio/x-wav,audio/mp3,audio/mpeg">

							@if ($errors->has('audio'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('audio') }}</strong>
								</span>
							@endif
						</div>
					</div>
				@endunless

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
