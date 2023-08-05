<?php

namespace Tests\Unit;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase ;

class TagTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function tag_belongs_to_many_blogs()
    {
        $tag=$this->createTag();
        //prepare
        $blog=$this->createBlog();
        //act
        $tag->blogs()->attach($blog->id);


        //assert
        $this->assertInstanceOf(Blog::class,$tag->blogs[0]);
    }
}
