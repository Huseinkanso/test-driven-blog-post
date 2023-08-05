<?php

namespace Tests\Unit;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     @test
     */
    public function user_can_has_many_blogs(): void
    {
        $user=$this->createUser();
        $blog=$this->createBlog(['user_id'=>$user->id]);


        // this return error because user has collection of blogs so we solve by
        // $user->blogs[0]
        //or  Collection::class instead of Blog::class
        $this->assertInstanceOf(Blog::class,$user->blogs[0]);
    }
}
