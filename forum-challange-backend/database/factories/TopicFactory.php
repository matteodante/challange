<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->firstOrCreate()->id; // Ottiene casualmente un user esistente
        $title = $this->faker->sentence;
        $slug = Str::slug($title, '-');
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph,
            'user_id' => $user,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
