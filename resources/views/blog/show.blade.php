@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <p class="float-left">{{$blog->title}}</p>
                        <p class="float-right">Created by - {{$blog->user->name}}</p>
                    </div>
                    <a class="float-right btn btn-sm btn-warning" href="{{'/blog/'.$blog->slug.'/edit'}}">Edit</a>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <img class="float-center" src={{"/storage/{$blog->image}"}} width="50%" />
                    </div>
                    <p>{{$blog->body}}</p>
                    Tags:
                    @foreach($blog->tags as $tag)
                    <button class="btn btn-sm btn-info text-white">
                        {{$tag->name}}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection