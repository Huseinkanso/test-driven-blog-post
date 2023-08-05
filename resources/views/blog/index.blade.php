@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Blogs
                    @auth
                    <a class="float-right btn btn-sm btn-success" href="{{route('blog.create')}}">Create</a>
                    @endauth
                </div>

                <div class="card-body row">
                    @foreach($blogs as $blog)
                    <a href="{{asset('/blog/'.$blog->slug)}}" class="d-flex pb-2 col-10">
                        <img src={{"/storage/blogs/{$blog->image}"}} width="50" />
                        <p class="ml-4">
                            {{$blog->title}}
                        </p>
                    </a>
                    <div class="float-right">
                        @can('update',$blog)
                        <a class="btn btn-sm btn-warning" href="{{route('blog.edit',$blog->slug)}}">Edit</a>
                        @endcan

                        @can('delete',$blog)
                        <a class="btn btn-sm btn-danger" href="{{route('tag.edit',$blog->slug)}}" onclick="event.preventDefault();
                            document.getElementById('delete-form-{{$blog->id}}').submit();">Delete</a>
                        <form id="{{'delete-form-'.$blog->id}}" action="{{ route('blog.destroy',$blog->slug) }}" method="POST" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>
                        @endcan
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> @endsection