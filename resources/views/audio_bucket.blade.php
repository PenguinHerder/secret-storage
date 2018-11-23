@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Available Audio Files</div>

                <div class="card-body">
                    <table class="table table-striped">
						<thead>
							<tr>
								<td>Date taken</td>
								<td>Name</td>
								<td>Description</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							@foreach($bucket->resources as $audio)
							<tr>
								<td>{{ $audio->date_taken }}</td>
								<td>{{ $audio->name }}</td>
								<td>{{ $audio->description }}</td>
								<td><a href="{{ route('audio', ['id' => $audio->id]) }}">Listen</a></td>
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
