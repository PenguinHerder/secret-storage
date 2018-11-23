@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
					{{ $audio->name }}
					<a href="{{ route('home') }}" class="float-right">Back</a>
				</div>

                <div class="card-body">
					<div class="row">
						<div class="col-md-8">
							<table class="table">
								<tbody>
									<tr>
										<td>Date taken</td>
										<td>{{ $audio->date_taken }}</td>
									</tr>
									<tr>
										<td>Description</td>
										<td>{{ $audio->description }}</td>
									</tr>
									<tr>
										<td>
											<a href="{{ route('raw_download', ['id' => $audio->id]) }}">Download</a></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-4">
							<audio controls src="{{ route('raw_audio', ['id' => $audio->id]) }}" style="width:100%">
									Your browser does not support the
									<code>audio</code> element.
							</audio>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
