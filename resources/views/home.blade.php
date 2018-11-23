@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link" href="{{ route('groups.index') }}">Groups</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('buckets') }}">Buckets</a>
						</li>
						@can('view', App\Models\User::class)
							<li class="nav-item">
								<a class="nav-link" href="{{ route('users') }}">Users</a>
							</li>
						@endcan
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
