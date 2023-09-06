<button type="submit" id="upload-button" class="btn btn-primary">Upload video</button>

<div class="progress mt-3" style="height: 25px">
    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%"></div>
</div>
{{-- <form method="POST" action="{{route('upload')}}" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="secret" value="{{auth()->user()->tokens()->exists() ? auth()->user()->tokens->first()->name : null}}">
    <input type="hidden" name="email" value="test@email.com">
    <input type="hidden" name="origin" value="local">
    <input type="hidden" name="user_id" value="1">
    <input type="hidden" name="piece_id" value="1">

    <div class="mb-3">
      <input class="form-control" type="file" id="video" name="video" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form> --}}