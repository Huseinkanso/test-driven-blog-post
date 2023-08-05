<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogValidationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        // without this we will get Error: Call to a member function connection() on null
        parent::setUp();
        $this->withExceptionHandling();
        $this->createAuthUser();

        $this->get('/blog/create');
    }
    /** @test*/
    public function while_storing_blog_fields_is_required()
    {
        $this->withExceptionHandling();
        $this->createAuthUser();

        $this->get('/blog/create');
        $res=$this->post('/blog')->assertRedirect('/blog/create');


        $res->assertSessionHasErrors(['title','body','image']);
    }
    /** @test*/
    public function while_storing_blog_image_field_must_be_image()
    {

        $res=$this->post('/blog',['image'=>'test'])->assertRedirect('/blog/create');
        // dd(session()->all());

        $res->assertSessionHasErrors(['title','body','image']);
    }

}
