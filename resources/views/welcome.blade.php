@extends('layouts.app')

@section('content')
@auth
<div class="container mb-4">
    <div class="mb-3">
        <a href="{{config('filesystems.disks.gcs.bucketUrl')}}" target="_blank">See GCS bucket</a> |
        <a href="/horizon" target="_blank">Horizon dashboard</a>
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