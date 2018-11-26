@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		<span>Group {{ $group->name }}</span>
		
		@can('create', [App\Models\Bucket::class, $group])
		<small>
			<a href="{{ route('buckets.create', ['group' => $group->id]) }}">(<i class="fa fa-plus"></i> new bucket)</a>
		</small>
		@endcan
	</h4>
	
	<div class="row">
		@foreach($group->buckets as $bucket)
		<div class="col-md-4">
			<div class="card">
				<a href="{{ route('buckets.show', ['id' => $bucket->id]) }}">
					<div class="card-header">
						<span>{{ $bucket->name }}</span>
						<i class="fa {{ bucket_icon($bucket->type) }}"></i>
					</div>
				</a>
				<div class="card-body">
					{{ $bucket->description }}
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
@endsection
