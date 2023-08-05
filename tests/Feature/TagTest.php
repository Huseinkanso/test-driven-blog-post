<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_create_a_tag()
    {
        //prepare

        //act
        $res=$this->post(route('tag.store'),['name'=>'laravel']);
        //assert
        $res->assertRedirect(route('tag.index'));
        $this->assertDatabaseHas('tags',['name'=>'laravel']);
    }

    /** @test */
    public function user_can_get_all_tags()
    {
        // prepare
        $tag=$this->createTag();
        // act
        $res=$this->get(route('tag.index'));
        // assert
        $res->assertStatus(200);
        $res->assertSee($tag->name);
    }

    /** @test */
    public function user_can_delete_a_tag()
    {
        $tag=$this->createTag();
        $res=$this->delete(route('tag.destroy',$tag->slug));

        $res->assertRedirect(route('tag.index'));
        $this->assertDatabaseMissing('tags',['name'=>$tag->name]);

    }
    /** @test */
    public function user_can_delete_a_tag_and_blog_link_also_deleted()
    {
        $blog=$this->createBlog();
        $tag=$this->createTag();
        $tag->blogs()->attach($blog->id);
        $res=$this->delete(route('tag.destroy',$tag->slug));

        $res->assertRedirect(route('tag.index'));
        $this->assertDatabaseMissing('tags',['name'=>$tag->name]);
        $this->assertDatabaseMissing('blog_tag',[
            'blog_id'=>$blog->id,
            'tag_id'=>$tag->id,
        ]);
        $this->assertDatabaseHas('blogs',['id'=>$blog->id]);

    }
    /** @test */
    public function user_can_update_a_tag()
    {
        $tag=$this->createTag();
        $res=$this->patch(route('tag.update',$tag->slug),['name'=>'new name']);

        $res->assertRedirect(route('tag.index'));
        $this->assertDatabaseHas('tags',['name'=>'new name']);
    }
    /** @test */
    public function user_can_visit_a_tag_create_page()
    {

        $res=$this->get(route('tag.create'));

        $res->assertOk();
        $res->assertSee('Create New Tag');
    }
    /** @test */
    public function user_can_edit_a_tag()
    {
        $tag=$this->createTag();
        $res=$this->get(route('tag.edit',$tag->slug));

        $res->assertOk();
        $res->assertSee('Update The Tag');
        $res->assertSee($tag->name);
    }
}
