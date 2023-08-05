@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Tags
                    <a class="float-right btn btn-sm btn-success" href="{{route('tag.create')}}">Create</a>
                </div>

                <div class="card-body">
                    @foreach($tags as $tag)
                    <div class="mb-5">
                        {{$tag->name}}
                        <div class="float-right">
                            <a class="btn btn-sm btn-warning" href="{{route('tag.edit',$tag->slug)}}">Edit</a>
                            <a class="btn btn-sm btn-danger" href="{{route('tag.edit',$tag->slug)}}" onclick="event.preventDefault();
                            document.getElementById('delete-form-{{$tag->id}}').submit();">Delete</a>
                        </div>
                        <form id="{{'delete-form-'.$tag->id}}" action="{{ route('tag.destroy',$tag->slug) }}" method="POST" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> @endsection