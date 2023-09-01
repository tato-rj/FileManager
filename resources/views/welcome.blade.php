<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{config('app.name')}}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <style type="text/css">
            .alert {
                position: fixed;
                bottom: 0;
                right: 16px;
            }
            
            button:focus,button:active,.btn:focus,.btn:active {
               outline: none !important;
               box-shadow: none !important;
            }

            .accordion-item,.btn,.form-control {
                border-radius: 0 !important;
            }

            .accordion-button:not(.collapsed) {
                background-color: inherit;
                color: inherit;
            }
        </style>
    </head>
    <body class="py-4">
        <div class="container mb-4">
            <div class="mb-4 d-flex align-items-baseline">
                <h1 class="border-bottom border-5 d-inline me-2">File Manager</h1>
                <h3 class="text-muted">for PianoLIT</h3>
            </div>
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

        <div class="container">
            @if($message = session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{$message}}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            @endif

            @if($errors->first())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{$errors->first()}}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    </body>
</html>
