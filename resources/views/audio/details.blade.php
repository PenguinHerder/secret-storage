@extends('layouts.app')

@section('content')
<div class="container">
	<h4>
		<span>{{ $audio->name }}</span>
		@can('update', $audio->bucket)
			<a class="float-right" href="{{ route('audios.edit', $audio->id) }}">Edit</a>
		@endcan
	</h4>
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
							<a href="{{ route('raw_download', ['audio' => $audio->id]) }}">Download</a>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<audio-panel
				:audio='{{ json_encode($audio) }}'
				:user-id='{{ Auth::user()->id }}'
				audio-uri='{{ route('raw_audio', ['audio' => $audio->id]) }}'
				save-analysis-uri='{{ route('save_analysis', ['audio' => $audio->id]) }}'
				approve-analysis-uri='{{ route('approve_analysis', ['audio' => $audio->id]) }}'
				:can-approve="{{ Auth::user()->can('insert', [App\Models\Bucket::class, $audio->bucket]) ? 'true' : 'false' }}"></audio-panel>
		</div>
    </div>
</div>
@endsection
