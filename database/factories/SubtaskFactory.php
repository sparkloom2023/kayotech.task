<?php

namespace Database\Factories;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtask>
 */
class SubtaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subtask::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(), // Creates a new Task if not provided
            'title' => $this->faker->sentence(2), // e.g., "Write introduction"
            'status' => $this->faker->randomElement(['not_done', 'done']), // Matches your subtask status
            'due_date' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'), // e.g., "2025-09-10"
        ];
    }
}
