@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		Available Audio Files
	</div>
	
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
					<a href="{{ route('audios.show', ['audio' => $audio->id]) }}">{{ $audio->name }} ({{ $audio->date_taken }})</a>
				</div>
				<div class="card-body">
					{{ $audio->description }}
				</div>
			</div>
		</div>
		@endforeach
		
		@can('insert', [App\Models\Bucket::class, $bucket])
		<div class="col-md-4">
			<div class="card bg-info">
				<div class="card-header">
					<a href="{{ route('audios.create', ['bucket' => $bucket->id]) }}"><i class="fa fa-plus"></i> Add file</a>
				</div>
			</div>
		</div>
		@endcan
	</div>
</div>
@endsection
