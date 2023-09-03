<form method="POST" action="{{route('upload')}}" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="secret" value="{{auth()->user()->tokens->first()->name}}">
    <input type="hidden" name="email" value="test@email.com">
    <input type="hidden" name="id" value="1">

    <div class="mb-3">
      <input class="form-control" type="file" id="video" name="video" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>