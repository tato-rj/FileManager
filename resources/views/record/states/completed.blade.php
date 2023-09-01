<div class="accordion-item">
    <div class="accordion-header alert-success">
      <button class="accordion-button collapsed alert-success" type="button" data-bs-toggle="collapse" data-bs-target="#record-{{$video->id}}">@fa(['icon' => 'check']){{$video->created_at->toFormattedDateString()}} | {{$video->user_email}}</button>
    </div>
    <div id="record-{{$video->id}}" class="accordion-collapse collapse" data-bs-parent="#records-container">
      <div class="accordion-body">
        <div class="text-muted small mb-2">
            <label class="fw-bold">PROCESSING TIME</label>
            <div>{{$video->processing_time}} minutes</div>
            <label class="fw-bold">MIME TYPE</label>
            <div>{{$video->mimeType}}</div>
            <label class="fw-bold">ORIGINAL SIZE</label>
            <div>{{$video->original_size_mb}}</div>
            <label class="fw-bold">COMPRESSED SIZE</label>
            <div>{{$video->compressed_size_mb}} ({{$video->size_decrease_percentage}})</div>
        </div>

        <div class="d-flex">
                <a href="{{$video->video_url}}" target="_blank" class="btn btn-outline-primary btn-sm me-2">Video</a>

                <a href="{{$video->thumb_url}}" target="_blank" class="btn btn-outline-primary btn-sm me-2">Thumbnail</a>

                <a href="{{route('webhook.show', $video)}}" target="_blank" class="btn btn-outline-primary btn-sm me-2">JSON</a>

                <form action="{{route('delete', $video)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
        </div>
      </div>
    </div>
</div>