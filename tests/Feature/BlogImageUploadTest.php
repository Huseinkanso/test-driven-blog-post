<?php

namespace Tests\Feature;

use App\Models\Blog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogImageUploadTest extends TestCase
{
    /**
     * A basic feature test example.
     */
        use RefreshDatabase;
    /** @test */
    public function user_can_upload_image_along_with_blog_details()
    {
        $user=$this->createAuthUser();
        // make user authenticated in test
        $this->actingAs($user);
        // Storage::fake();
        $blog=Blog::factory()->raw();
        // dd($blog);

        // dd($image);

        $res=$this->post('blog',$blog);


        $this->assertDatabaseHas('blogs',['image'=>$blog['image']->name]);
        Storage::disk('public')->assertExists('blogs/photo1.jpg');
    }
    /** @test */
    public function user_can_change_image_when_updating_blog()
    {
        $user=$this->createAuthUser();
        // make user authenticated in test
        $this->actingAs($user);
        // Storage::fake();
        // here i will overwrite image for image name becaause i cant get image name to test
        // in real i dont need this
        $blog=$this->createBlog(['image'=>'photo1.jpg']);
        // dd($blog);
        $newImage=UploadedFile::fake()->image('photo2.jpg');
        // dd($image);

        $res=$this->patch(route('blog.update',$blog->slug),['image'=>$newImage]);


        $this->assertDatabaseHas('blogs',['image'=>$newImage->getClientOriginalName()]);
        Storage::disk('public')->assertExists('blogs/photo2.jpg');
        Storage::disk('public')->assertMissing('blogs/photo1.jpg');
    }
    /** @test */
    public function while_deleting_blog_image_is_deleted()
    {
        $user=$this->createAuthUser();

        $this->actingAs($user);

        $blog=$this->createBlog();
        Storage::disk('public')->put('blogs/'.$blog->image->name,file_get_contents($blog->image));
        Storage::disk('public')->assertExists('blogs/photo1.jpg');
        $blog->update(['image'=>'photo1.jpg']);
        $res=$this->delete(route('blog.destroy',$blog->slug));


        $this->assertDatabaseMissing('blogs',['id'=>$blog->id]);

        Storage::disk('public')->assertMissing('blogs/photo1.jpg');
    }
}
