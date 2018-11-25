@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		{{ $group->name }}
	</div>
	
	<div class="row">
		@foreach($group->buckets as $bucket)
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

		@can('create', [App\Models\Bucket::class, $group])
		<div class="col-md-4">
			<div class="card bg-info">
				<div class="card-header">
					<a href="{{ route('buckets.create', ['group' => $group->id]) }}"><i class="fa fa-plus"></i> New</a>
				</div>
			</div>
		</div>
		@endcan
	</div>
</div>
@endsection
