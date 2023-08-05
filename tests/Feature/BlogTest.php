<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{

    // laravel provide  a trait to run migration before test if there is no migration
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        // $user=$this->createUser();
        // // make user authenticated in test
        // $this->actingAs($user);
        $this->createAuthUser();
    }

    /**
     * A basic feature test example.
     */
    /**  @test */
    public function user_can_see_all_published_blogs(): void
    {
        // past / scene / prepare
        $blog= $this->createBlog(['published_at'=>now()],2);
        $unpublished=$this->createBlog();
        // dd($blog);
        $blog2=$this->createBlog(['title'=>'first blog 2']);

        // present / action / act
        $response = $this->get('/blog');
        // future / assertion / assert
        $response->assertStatus(200);
        $response->assertSee($blog[0]->title);
        $response->assertSee($blog[1]->title);


        $response->assertDontSee($unpublished->title);
    }
    /**  @test */
    public function user_can_see_all_his_unpublished_or_unpublished_blogs(): void
    {
        // past / scene / prepare
        $publishBlog= $this->createBlog(['published_at'=>now()],2);
        $unpublishBlog= $this->createBlog([],2);
        $user2Blog=$this->createBlog(['user_id'=>22],2);
        // dd($blog);


        // present / action / act
        $response = $this->get(route('blog.all'));
        // future / assertion / assert
        $response->assertStatus(200);
        $response->assertSee($publishBlog[0]->title);
        $response->assertSee($publishBlog[1]->title);
        $response->assertSee($unpublishBlog[1]->title);
        $response->assertSee($unpublishBlog[0]->title);
        $response->assertDontSee($user2Blog[0]->title);

    }
    /** @test */
    public function user_cant_visit_unpublished_single_blog(): void
    {
        // here should return 404 but if exeptionhandling disabled the test will not pass
        // because result of the test is exeption
        $this->withExceptionHandling();
        // past / scene / prepare
        $blog= $this->createBlog();
        // dd($blog->slug);
        // present / action / act
        $response = $this->get('/blog'.$blog->slug);
        // future / assertion / assert
        $response->assertStatus(404);
        $response->assertDontSee($blog->title);

    }
    /** @test */
    public function user_can_visit_a_published_single_blog()
    {
        //prepare
        $user=$this->createUser();
        $blog=$this->createBlog(['published_at'=>now(),'user_id'=>$user->id]);
        $tags=$this->createTag([],2);
        $blog->tags()->attach($tags->pluck('id')->toArray());
        // dd($blog[0]->title);
        // act
        $res=$this->get('/blog/'.$blog->slug);
        //assert
        $res->assertStatus(200);
        $res->assertSee($blog->title);
        $res->assertSee($blog->body);
        $res->assertSee($blog->user->name);
        $res->assertSee($blog->tags[0]->name);
    }

    /** @test */
    /*public function user_can_store_blog()
    {
        // prepare
        // $data=['title'=>'my post title','body'=>'body ghgh'];
        // to use factory in blog data we need to create blogFactory
        // to have different data we need to use fake() in our array in blogFactory
        // ->make => return the hole created item
        // ->create => return the item and store it in db
        // ->raw => return only the created item from factory
        $user=User::factory()->create();
        $blog=$this->createBlog()->toArray();
        $data=array_merge(['user_id'=>$user->id],$blog);

        // dd($data);
        // act
        $res=$this->post('blog',$data);
        // assert
        $res->assertStatus(302);
        $res->assertRedirect('/blog');
        $this->assertDatabaseHas('blogs',[
            'image'=>$blog['image']->name,
            'user_id'=>$user->id,
        ]);
    }*/
    /** @test */
    public function only_authenticated_user_can_store_blog()
    {
        // $user=$this->createUser();
        // // make user authenticated in test
        // $this->actingAs($user);
        // dd(auth());
        $blog=$this->createBlog()->toArray();
        $tags=$this->createTag([],2);
        // dd($tags->pluck('id'));
        // pluck can grap specified key and return collection
        $data=array_merge(['tag_ids'=>$tags->pluck('id')->toArray()],$blog);
        unset($blog['user_id']);
        $res=$this->post('blog',$data);

        $res->assertStatus(302);
        $res->assertRedirect('/blog');
        $this->assertDatabaseHas('blogs',[
            'image'=>$blog['image']->name,
            // 'user_id'=>auth()->id(),
        ]);
        $this->assertDatabaseHas('blog_tag',
        [
            'tag_id'=>$tags[0]->id,
        ]
    );
    }
    /** @test */
    public function user_can_delete_blog()
    {
        // $user=User::factory()->create();

        // prepare
        $blog=$this->createBlog();
        //act
        $tag=$this->createTag();
        $blog->tags()->attach($tag->id);
        $res=$this->delete('/blog/'.$blog->slug);
        //assert
        $res->assertRedirect('/blog');
        $this->assertDatabaseMissing('blogs',['title'=>$blog['title']]);
        $this->assertDatabaseMissing('blog_tag',['blog_id'=>$blog['id']]);
    }
    /** @test */
    public function user_can_update_blog_details()
    {
        //prepare
        $blog=$this->createBlog(['user_id'=>auth()->id()]);
        $tag=$this->createTag([],2);
        //act
        $blog->tags()->attach($tag->pluck('id')->toArray());
        $res=$this->patch('blog/'.$blog->slug,
        [
            'title'=>'updated title',
            'body'=>'body',
            'tag_ids'=>$tag[0]->id,
        ]);
        //assert
        $res->assertRedirect('/blog');
        $this->assertDatabaseHas('blogs',['title'=>'updated title']);
        $this->assertDatabaseMissing('blog_tag',
        [
            'blog_id'=>$blog->id,
            'tag_id'=>$tag[1]->id,
        ]);
    }

    /** @test */

    public function user_can_visit_form_to_store_blog()
    {
        //prepare

        // act
        $res=$this->get('/blog/create');

        //assert
        $res->assertStatus(200);
        $res->assertSee('Create New Blog');
    }
    /** @test */
    public function user_can_visit_blog_update()
    {

        // prepare
        $blog=$this->createBlog();
        //act
        $res=$this->get('/blog/'.$blog->slug.'/edit');
        //assert
        $res->assertStatus(200);
        // $res->assertSee('blog updated');
        $res->assertSee($blog->title);
    }

}
