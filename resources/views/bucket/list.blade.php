@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		Your Buckets
	</h4>
	
	<div class="row">
		@foreach($buckets as $bucket)
		<div class="col-md-4">
			<div class="card">
				<a href="{{ route('buckets.show', ['id' => $bucket->id]) }}" style="display: block;">
					<div class="card-body">
						<h5>
							{{ $bucket->name }}
						</h5>
						<hr>
						<p>
							{{ $bucket->description }}
						</p>
					</div>
				</a>
			</div>
		</div>
		@endforeach
	</div>
</div>

@if($all !== null && !$all->isEmpty())
	<div class='container'>
		<div class='alert alert-secondary'>
			Buckets belonging to groups you are NOT a member of
		</div>

		<div class="row">
			@foreach($all as $bucket)
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						<a href="{{ route('buckets.show', ['id' => $bucket->id]) }}">
							{{ $bucket->name }}
							<i class="fa {{ bucket_icon($bucket->type) }}"></i>
						</a>
					</div>
					<div class="card-body">
						{{ $bucket->description }}
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endif

@endsection
