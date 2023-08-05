<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// the testcase here is extended from phpunit
// in this we cant use facade and database so we will change to


class BlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     @test
     */
    public function blog_can_upload_its_image()
    {
        //prepare
        $blog=new Blog();
        $image=UploadedFile::fake()->image('photo1.jpg');

        //act

        $blog->uploadImage($image);


        //assert
        Storage::disk('public')->assertExists('blogs/photo1.jpg');
    }
    /** @test */
    public function blog_belongs_to_user()
    {
        $user=$this->createUser();
        //prepare
        $blog=$this->createBlog(['user_id'=>$user->id]);
        //act

        //assert
        $this->assertInstanceOf(User::class,$blog->user);
    }
    /** @test */
    public function test_can_get_all_its_tag_ids_in_an_array()
    {
        $blog=$this->createBlog();
        $tag=$this->createTag([],4);

        $blog->tags()->attach($tag->pluck('id'));

        $this->assertIsArray($blog->tagIds());
        $this->assertEquals(4,count($tag->pluck('id')));
        $this->assertEquals($tag[3]->id,$blog->tagIds()[3]);

        // $this->assertArrayHasKey($tag[0]->id,$blog->tagIds);
    }
    /** @test */
    public function blog_has_published_at_field_formated()
    {
        $time=now();
        $blog=$this->createBlog(['published_at'=>$time]);
        $this->assertEquals($time->format('Y-m-d\TH:m'),$blog->published_at);

    }
}
