<?php

namespace Database\Factories;

use App\Models\Dance;
use Illuminate\Database\Eloquent\Factories\Factory;

class DanceFactory extends Factory
{
    protected $model = Dance::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(2, true),
            'category'    => 'banaue',
            'description' => $this->faker->sentence(),
            'video_url'   => null,
            'video_path'  => null,
            'image_path'  => null,
        ];
    }
}
