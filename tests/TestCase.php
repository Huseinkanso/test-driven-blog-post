<?php

namespace Tests;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    // setUp is a function in baseTestcase and run before any test we overwrite the method and add condition for the test here
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    protected function createBlog($args = [],$num=null)
    {
        // $title=$title?? 'simple ever blog';
        // $body=$body?? 'simple null body';
        // return Blog::create(['title'=>$title,'body'=>$body]);

        // i can provide array of values in create
        return Blog::factory($num)->create($args);
    }

    public function createUser($args=[],$num=null) : User
    {
        return User::factory($num)->create($args);
    }
    public function createTag($args=[],$num=null)
    {
        return Tag::factory($num)->create($args);
    }

    public function createAuthUser($args = [])
    {
        $user=$this->createUser();
        // make user authenticated in test
        $this->actingAs($user);

        return $user;
    }
}
