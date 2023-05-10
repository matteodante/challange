<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topic = Topic::inRandomOrder()->first(); // Ottiene casualmente un topic esistente

        return [
            'text' => $this->faker->paragraph,
            'topic_id' => $topic->id,
            'user_id' => $topic->user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
