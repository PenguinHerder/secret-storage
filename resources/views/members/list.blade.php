@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		Users
	</div>
	
	<div class="row">
		@foreach($users as $user)
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					{{ $user->name }}
				</div>
				<div class="card-body">
						{{ $user->email }}<br>
						{{ $user->role->name }}<br>
						<hr>
						{{ $user->groups->pluck('name')->implode(', ') }}
				</div>
			</div>
		</div>
		@endforeach

		@can('create', App\Models\User::class)
		<div class="col-md-4">
			<div class="card bg-info">
				<div class="card-header">
					<a href="{{ route('members.create') }}"><i class="fa fa-plus"></i> Add</a>
				</div>
			</div>
		</div>
		@endcan
	</div>
</div>
@endsection
