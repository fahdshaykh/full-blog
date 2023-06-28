@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
        
            {{ $post->title }}


        <p>{{ $post->content }}</p>

        <h4>{{ __('Comments') }}</h4>
    </div>

@endsection('content')