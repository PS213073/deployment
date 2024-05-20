<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_logged_in_user_can_create_a_new_post()
    {
        // // create user
        // $user = User::factory()->create();

        // // login user
        // $response = $this->post('login', [
        //     'email' => $user->email,
        //     'password' => 'password',
        // ]);

        $this->setUpUser();

        $this->assertAuthenticated();

        // create post
        $response = $this
            ->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => 'test title',
                'description' => 'test description'
            ]);

        // check post count
        $this->assertEquals(1, Post::count());

        // check response redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('posts.index'));

        // check if user id is same as in database
        $post = Post::first();
        $post->refresh();
        // $this->assertEquals($post->user_id, $user->id);
        $this->assertEquals($post->user_id, $this->user->id);

        // check if post title is equal to the one we created
        $this->assertEquals($post->title, 'test title');

        // check if post description is equal to the one we created
        $this->assertEquals($post->description, 'test description');
    }


    public function test_a_logged_in_user_can_view_posts()
    {
        $this->setUpUser();

        $this->assertAuthenticated();

        // create post
        $response = $this
            ->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => 'test title',
                'description' => 'test description'
            ]);

        // check post count
        $this->assertEquals(1, Post::count());


        // check response redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('posts.index'));
    }

    public function test_a_logged_in_user_can_update_posts()
    {
        $this->setUpUser();

        $this->assertAuthenticated();

        // create post
        $response = $this
            ->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => 'test title',
                'description' => 'test description'
            ]);

        // update post
        $post = Post::first();
        $response = $this->put(route('posts.update', $post->id), [
            'title' => 'updated title',
            'description' => 'updated description',
        ]);

        // check updated post
        $updated_post = Post::first();
        $this->assertEquals('updated title', $updated_post->title);
        $this->assertEquals('updated description', $updated_post->description);
    }

    public function test_a_logged_in_user_can_delete_post()
    {
        $this->setUpUser();

        $this->assertAuthenticated();

        // create post
        $response = $this
            ->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => 'test title',
                'description' => 'test description'
            ]);

        // delete post
        $post = Post::first();
        $response = $this->delete(route('posts.destroy', $post->id));

        // validate
        $this->assertEquals(0, Post::count());
    }
}
