@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
					Groups
					@can('create', App\Models\Group::class)
					<a class='float-right' href='{{ route('groups.create') }}'>+New</a>
					@endcan
				</div>

                <div class="card-body">
                    <table class="table table-striped">
						<thead>
							<tr>
								<td>Name</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							@foreach($groups as $group)
							<tr>
								<td>{{ $group->name }}</td>
								<td><a href="{{ route('groups.show', ['id' => $group->id]) }}">Details</a></td>
							</tr>
							@endforeach
							
							@if($all !== null)
							<tr>
								<td colspan='2'>Other groups you are NOT a member of</td>
							</tr>
							
								@foreach($all as $group)
								<tr>
									<td>{{ $group->name }}</td>
									<td><a href="{{ route('groups.show', ['id' => $group->id]) }}">Details</a></td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
