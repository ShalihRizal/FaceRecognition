<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_data_name' => $this->faker->word(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }
}
