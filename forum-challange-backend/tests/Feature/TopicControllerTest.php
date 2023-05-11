<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicsControllerTest extends TestCase
{

    public function testIndex()
    {
        $user = User::factory()->create();

        Topic::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/api/topics');
        //dd($response->json());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'topics' => [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'slug',
                            'user_id',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                    'path',
                    'per_page',
                    'next_page_url',
                    'prev_cursor',
                    'prev_page_url',
                ],
            ]);
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'New Topic',
            'description' => 'This is a new topic description.',
        ];

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->post('/api/topics', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Topic created successfully',
                'topic' => [
                    'title' => 'New Topic',
                    'description' => 'This is a new topic description.',
                ],
            ]);

        $this->assertDatabaseHas('topics', $data);
    }

    public function testShow()
    {
        $user = User::factory()->create();

        $topic = Topic::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->get('/api/topics/' . $topic->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'topic' => [
                    'title',
                    'description',
                    'slug',
                    'user_id',
                    'created_at',
                    'updated_at',
                ],
                'comments' => [
                    'data' => [
                        '*' => ['id', 'text']
                    ]
                ],
            ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();

        $topic = Topic::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = [
            'title' => 'Updated Topic',
            'description' => 'This is an updated topic description.',
        ];

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->put('/api/topics/' . $topic->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Topic updated successfully',
                'topic' => [
                    'title' => 'Updated Topic',
                    'description' => 'This is an updated topic description.',
                ],
            ]);

        $this->assertDatabaseHas('topics', $data);
    }

    public function testDelete()
    {
        $user = User::factory()->create();

        $topic = Topic::factory()->create([
            'user_id' => $user->id,
        ]);


        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->delete('/api/topics/' . $topic->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Topic deleted successfully',
                'topic' => [
                    'id' => $topic->id,
                ],
            ]);

        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
    }
}
