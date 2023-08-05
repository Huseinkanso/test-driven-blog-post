<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except('index','show');
    }
    public function index()
    {
        // Blog::where('published_at','!=',null)->get()
        // Blog::whereNotNull('published_at')->get()
        // Blog::published()->get()

        return view('blog.index',['blogs'=>Blog::published()->get()]);
    }
    public function create()
    {

        return view('blog.create',['tags'=>Tag::all()]);
    }
    public function show($blog)
    {
        // $blog=Blog::find($id);
        // with exeptionhandling we dont need to return response 404
        // if($blog->published_at==null)
        // {
        //     return response('',404);
        // }
        // dd($blog);
        $blog=Blog::where('slug',$blog)->published()->first();
        return view('blog.show')->with(['blog'=>$blog]);
    }

    public function edit(Blog $blog)
    {
        // dd($blog);
        return view('blog.edit',['blog'=>$blog,'tags'=>Tag::all()]);
    }
    public function store(BlogStoreRequest $request)
    {


        // dd($request->all());
        // $request['user-id']=auth()->id();
        // $blog=Blog::create($request->except('image'));

        // we can use the relationship to store the blog
        // if we use blogs  ===> give the collection of items empty
        // if we use blogs() ===> give the relation and the id of user to create the blog
        // $blog=auth()->user()->blogs()->create($request->except('image'));
        // Storage::put($request->image->name,file_get_contents($request->image));

        // $blog->uploadImage($request->image);
        // $blog->tags()->attach($request->tag_ids);
        // dd($request->image->getClientOriginalName());
        Blog::store($request);
        return redirect('blog');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete',$blog);
        $blog->deleteImage($blog->image);
        // Blog::destroy($id);

        // this use route model binding
        //that mean laravel bring the model from db by giving her id
        // should be the name of the var in route = name in parameter
        // dd($blog);
        // $blog->tags()->detach();
        $blog->delete();
        return redirect('/blog');

    }

    public function update(Request $request,Blog $blog)
    {
        // dd($blog);
        // dd($request->all());
        // dd($request->all());
        $this->authorize('update',$blog);
        $blog->edit($request);
        return redirect('/blog');

    }
    public function all()
    {
        return view('blog.all',['blogs'=>auth()->user()->blogs]);
    }
}
