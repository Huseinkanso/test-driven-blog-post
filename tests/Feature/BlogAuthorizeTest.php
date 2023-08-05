<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogAuthorizeTest extends TestCase
{
    /** @test */
    public function one_user_cannot_update_blog_of_auther_user()
    {
        $this->withExceptionHandling();
        $user=$this->createAuthUser();
        $blog=$this->createBlog();

        $res=$this->patch(route('blog.update',$blog->slug));

        $res->assertStatus(403);
    }
    /** @test */
    public function user_canot_delete_blog_of_other_user()
    {
        $this->withExceptionHandling();
        $user=$this->createAuthUser();
        $blog=$this->createBlog();

        $res=$this->patch(route('blog.destroy',$blog->slug));

        $res->assertStatus(403);
    }
}
