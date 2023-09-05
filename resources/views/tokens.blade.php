@extends('layouts.app')

@section('content')
@auth
<div class="container mb-4">
    <form method="POST" action="{{route('tokens.store')}}">
        @csrf
        <button type="submit" class="btn btn-primary">Create new token</button>
    </form>
</div>

<div class="container">
    
        @foreach(auth()->user()->tokens as $token)
        <div class="row mb-4"> 
            <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="small text-muted mb-0">Used since {{$token->created_at->diffForHumans()}}</label>
                    <form method="POST" action="{{route('tokens.revoke')}}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{$token->id}}">
                        <button class="btn-raw text-danger">Revoke</button>
                    </form>
                </div>
                <div class="text-truncate">
                <a href="" class="clip" data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-trigger="click"
                    data-bs-title="Copied!">{{$token->name}}</a>
                </div>
            </div>
        </div>
        @endforeach
    
</div>
@endauth
@endsection