<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BlogPost;
use App\Models\Comment;
use Faker\Factory;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_no_blog_post()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }

    public function test_when_post_exist_with_no_comments()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet!');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }

    public function test_see_blog_post_with_comments()
    {
        // Arrange
        $post = $this->createDummyBlogPost();
        \App\Models\Comment::factory(4)->create(['blog_post_id' => $post->id]);
        // Act
        $response = $this->get('/posts');

        //assert
        $response->assertSeeText('4 comments');
    }

    public function test_store_valid()
    {
        $params = [
            'title' => 'valid title',
            'content' => 'At leat 10 charachters'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function test_store_fail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');


        // dd($messages->getMessages());
    }

    public function test_update_valid()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        $data = $post->toArray();

        // dd($data['title']);

        $this->assertDatabaseHas('blog_posts', [
            'title' => $data['title'],
            'content' => $data['content']
        ]);

        $params = [
            'title' => 'A new named title',
            'content' => 'content as changed'
        ];
        
        $this->actingAs($this->user())
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');

        $this->assertDatabaseMissing('blog_posts', [
            'title' => $data['title'],
            'content' => $data['content']
        ]);

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title'
        ]);
        
    }

    public function test_delete()
    {
        $post = $this->createDummyBlogPost();

        $data = $post->toArray();

        $this->assertDatabaseHas('blog_posts', [
            'title' => $data['title'],
            'content' => $data['content']
        ]);


        $this->actingAs($this->user())
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!');

        $this->assertDatabaseMissing('blog_posts', [
            'title' => $data['title'],
            'content' => $data['content']
        ]);
    }

    private function createDummyBlogPost(): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();

        // the state factory is not working in larave 9
        return \App\Models\BlogPost::factory()->title()->create();

        // return \App\Models\BlogPost::factory()->create();
        // return $post;
    }

}
