@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
					Users
					@can('create', App\Models\User::class)
					<span class='float-right'>+New</span>
					@endcan
				</div>

                <div class="card-body">
                    <table class="table table-striped">
						<thead>
							<tr>
								<td>Name</td>
								<td>Email</td>
								<td>Role</td>
								<td>Groups</td>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $user)
							<tr>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>{{ $user->role->name }}</td>
								<td>{{ $user->groups->pluck('name')->implode(', ') }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
