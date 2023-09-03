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
    <div class="d-flex align-items-center mb-2">
    <form method="POST" action="{{route('tokens.revoke')}}" class="me-2">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" value="{{$token->id}}">
        <button class="btn btn-sm btn-danger">Revoke</button>
    </form>
    <a href="" class="clip d-inline" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-trigger="click"
        data-bs-title="Copied!">{{$token->name}}</a>
    </div>
    @endforeach
</div>
@endauth
@endsection