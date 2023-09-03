@extends('layouts.app')

@section('content')
@auth
<div class="container mb-4">
    <p><a href="{{config('filesystems.disks.gcs.bucketUrl')}}" target="_blank">See GCS bucket</a></p>

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