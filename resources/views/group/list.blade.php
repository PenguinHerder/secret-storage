@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		<span>Groups you are members of</span>
		@can('create', App\Models\Group::class)
		<small>
			<a href="{{ route('groups.create') }}">(<i class="fa fa-plus"></i> create new group)</a>
		</small>
		@endcan
	</h4>

	@if($groups->isEmpty())
	<div class="alert alert-warning">
		You are not a member of any group
	</div>
	@endif
	
	<div class="row">
		@foreach($groups as $group)
		<div class="col-md-4">
			<div class="card">
				<a href="{{ route('groups.show', ['group' => $group->id]) }}" style="display: block;">
					<div class="card-body">
						<h5>{{ $group->name }}</h5>
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
			Other groups you are NOT a member of
		</div>

		<div class="row">
			@foreach($all as $group)
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						<a href="{{ route('groups.show', ['group' => $group->id]) }}">{{ $group->name }}</a>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endif

@endsection
