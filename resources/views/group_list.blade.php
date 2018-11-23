@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		Groups
	</div>

	<div class="row">
		@foreach($groups as $group)
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<a href="{{ route('groups.show', ['group' => $group->id]) }}">{{ $group->name }}</a>
				</div>
			</div>
		</div>
		@endforeach

		@can('create', App\Models\Group::class)
		<div class="col-md-4">
			<div class="card bg-info">
				<div class="card-header">
					<a href="{{ route('groups.create') }}"><i class="fa fa-plus"></i> New</a>
				</div>
			</div>
		</div>
		@endcan
	</div>
</div>

@if($all !== null)
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
