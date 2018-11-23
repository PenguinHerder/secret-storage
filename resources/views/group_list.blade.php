@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Groups</div>

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
								<td><a href="{{ route('group', ['id' => $group->id]) }}">Details</a></td>
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
