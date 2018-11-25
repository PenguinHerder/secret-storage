@extends('layouts.app')

@section('content')
<div class="container">
	<div class="alert alert-primary">
		{{ $audio->name }}
	</div>
	<div class="row">
		<div class="col-md-6">
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
						<td>Duration</td>
						<td>{{ secondsToHuman($audio->duration) }}</td>
					</tr>
					<tr>
						<td>Filesize</td>
						<td>
							{{ bytesToHuman($audio->filesize) }}
							<small>(original {{ bytesToHuman($audio->upload_filesize) }})</small>
						</td>
					</tr>
					<tr>
						<td>
							<a href="{{ route('raw_download', ['id' => $audio->id]) }}">Download</a>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<audio-panel
				:audio='{{ json_encode($audio) }}'
				:is-owner='{{ $audio->bucket->owner_id == Auth::user()->id ? 'true' : 'false' }}'
				uri='{{ route('raw_audio', ['id' => $audio->id]) }}'></audio-panel>
		</div>
    </div>
	
	
</div>
@endsection
