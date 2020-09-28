<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosttoTimeLineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_post_a_text_post() {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/posts', [
            'data' => [
                'type'=>'post',
                'attributes'=>[
                    'body'=>'Testing body',
                ]
            ]
        ]);

        $post = Post::first();

        $this->assertCount(1, Post::all());
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('Testing body', $post->body);

        $response->assertStatus(201)
            ->assertJson([
                'data'=>[
                    'type'=>'posts',
                    'post_id'=>$post->id,
                    'attributes'=>[
                        'body'=>$post->body
                    ]
                ],
                'links'=>[
                    'self'=>url('/posts/'.$post->id),
                ]
            ]);
    }
}
