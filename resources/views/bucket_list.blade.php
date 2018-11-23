@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Buckets:</div>

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
							@foreach($buckets as $bucket)
							<tr>
								<td>{{ $bucket->type }}</td>
								<td>{{ $bucket->name }}</td>
								<td>{{ $bucket->description }}</td>
								<td><a href="{{ route('bucket', ['id' => $bucket->id]) }}">Details</a></td>
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
