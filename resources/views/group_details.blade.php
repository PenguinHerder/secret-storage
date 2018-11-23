@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
					{{ $group->name }}
					@can('create', App\Models\Bucket::class)
					<a href="{{ route('buckets.create', ['group' => $group->id]) }}" class="float-right">Add a bucket</a>
					@endcan
				</div>

                <div class="card-body">
					<table class="table table-striped">
						<thead>
							<tr>
								<td>Type</td>
								<td>Name</td>
								<td>Description</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							@foreach($group->buckets as $bucket)
							<tr>
								<td>{{ $bucket->type }}</td>
								<td>{{ $bucket->name }}</td>
								<td>{{ $bucket->description }}</td>
								<td><a href="{{ route('buckets.show', ['id' => $bucket->id]) }}">Details</a></td>
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
