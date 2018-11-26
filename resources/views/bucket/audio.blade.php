@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		<span>Available audio files in &quot;{{ $bucket->name }}&quot; bucket</span>
		
		@can('insert', [App\Models\Bucket::class, $bucket])
		<small>
			<a href="{{ route('audios.create', ['bucket' => $bucket->id]) }}">(<i class="fa fa-plus"></i> add file)</a>
		</small>
		@endcan
	</h4>
	
	@if($bucket->resources->isEmpty())
		<div class='alert alert-warning'>
			There are no files in this bucket
		</div>
	@endif
	
	<div class="row">
		@foreach($bucket->resources as $audio)
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					@if($audio->status == App\PH\C::FILE_STATUS_READY)
						<a href="{{ route('audios.show', ['audio' => $audio->id]) }}" style="display: block;">{{ $audio->name }} ({{ $audio->date_taken }})</a>
					@else
						<span>
							{{ $audio->name }} (processing...) <i class='fa fa-cogs'></i>
						</span>
					@endif
				</div>
				<div class="card-body">
					{{ $audio->description }}
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
@endsection
