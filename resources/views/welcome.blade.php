@extends('layouts.app')

@section('content')
@auth
<div class="container mb-4">
    <div class="mb-3">
        <a href="{{route('tokens.index')}}">Api tokens</a>
        |
        <a href="{{config('filesystems.disks.gcs.bucketUrl')}}" target="_blank">See GCS bucket</a>
        |
        <a href="/horizon" target="_blank">Horizon dashboard</a>
        @if(app()->environment() == 'local')
        |
        <a href="{{env('DROPLET_IP')}}" target="_blank">Live server</a>
        @endif
    </div>

    @include('record.create')
</div>

<div class="container">
    <div class="accordion shadow-lg mb-4" id="records-container">
    @foreach($videos as $video)
        @if($video->completed())
        @include('record.states.completed')
        @else
        @include('record.states.pending')
        @endif
    @endforeach
    </div>

    {{$videos->links()}}
</div>
@endauth
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js"></script>
@auth
<script type="text/javascript">
let uploadButton = $('#upload-button');
let resumable = new Resumable({
    target: '{{ route('upload') }}',
    query:{
        _token:'{{ csrf_token() }}',
        secret:'{{auth()->user()->tokens()->exists() ? auth()->user()->tokens->first()->name : null}}',
        origin: 'local',
        user_id: 1,
        piece_id: 1,
        email: 'test@email.com'
    },
    fileType: ['mp4', 'MOV'],
    maxFileSize: 500000000,
    headers: {
        'Accept' : 'application/json'
    },
    testChunks: false,
    throttleProgressCallbacks: 1,
});

resumable.assignBrowse(uploadButton[0]);
resumable.on('fileAdded', function (file) {
    showProgress();
    uploadButton.prop('disabled', true);
    resumable.upload();
});

resumable.on('fileProgress', function (file) {
    updateProgress(Math.floor(file.progress() * 100));
});

resumable.on('fileSuccess', function (file, response) {
    console.log('Finished upload');

    setTimeout(function() {
        location.reload();
    }, 2000);
});

resumable.on('fileError', function (file, response) {
    console.log(response);
    alert('File uploading error.');
});
</script>
<script type="text/javascript">
let progress = $('.progress');
function showProgress() {
    progress.find('.progress-bar').css('width', '0%');
    progress.find('.progress-bar').html('0%');
    progress.find('.progress-bar').removeClass('bg-success');
    progress.show();
}
function updateProgress(value) {
    progress.find('.progress-bar').css('width', `${value}%`);
    progress.find('.progress-bar').html(`${value}%`);
}

function hideProgress() {
    progress.hide();
}
</script>
@endauth
@endpush