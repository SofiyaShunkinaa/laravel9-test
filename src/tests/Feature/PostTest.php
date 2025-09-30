<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_posts_list()
    {
        Post::factory()->count(5)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'content', 'created_at', 'updated_at']
                 ]);
    }

    /** @test */
    public function user_can_search_posts_by_title()
    {
        Post::factory()->create(['title' => 'Laravel Testing']);
        Post::factory()->create(['title' => 'Another Post']);

        $response = $this->getJson('/api/posts?search=Laravel');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['title' => 'Laravel Testing']);
    }

    /** @test */
    public function user_can_filter_posts_by_some_field()
    {
        Post::factory()->create(['title' => 'Published Post', 'status' => 'published']);
        Post::factory()->create(['title' => 'Draft Post', 'status' => 'draft']);

        $response = $this->getJson('/api/posts?status=published');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['title' => 'Published Post']);
    }
}
