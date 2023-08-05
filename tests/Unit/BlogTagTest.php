<?php

namespace Tests\Unit;


use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTagTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function blog_has_many_tags()
    {
        //prepare
        $tag=$this->createTag();
        $blog=$this->createBlog();

        $blog->tags()->attach($tag->id);
        //act
        // dd($blog->tags);
        //assert
        $this->assertInstanceOf(Tag::class,$blog->tags[0]);
    }

}
