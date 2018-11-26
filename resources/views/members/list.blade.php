@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		<span>All members</span>
		
		@can('add', App\Models\User::class)
		<small>
			<a href="{{ route('members.create') }}">(<i class="fa fa-plus"></i> add)</a>
		</small>
		@endcan
	</h4>
	
	<div class="row">
		@foreach($users as $user)
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					{{ $user->name }}
				</div>
				<div class="card-body">
						{{ $user->email }}<br>
						Permissions: {{ $user->role->name }}<br>
						<hr>
						Groups: {{ $user->groups->pluck('name')->implode(', ') }}
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
@endsection
