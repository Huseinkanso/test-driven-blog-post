<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPublishTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     use RefreshDatabase;

     public function setUp():void
     {
        parent::setUp();
        $this->createAuthUser();
     }
    /** @test */
    public function only_auth_user_can_publish_blog()
    {


        $blog=$this->createBlog();

        $response = $this->patch('blog/'.$blog->slug,['published_at'=>now()]);

        $response->assertRedirect('blog');
        // fresh is for getting the same from db
        $this->assertNotNull($blog->fresh()->published_at);
    }
    /** @test */
    public function user_can_unpublish_blog()
    {

        $blog=$this->createBlog(['published_at'=>now()]);

        $response = $this->patch('blog/'.$blog->slug,['published_at'=>null]);

        $response->assertRedirect('blog');
        // fresh is for getting the same from db
        $this->assertNull($blog->fresh()->published_at);
    }
}
