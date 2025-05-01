<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // e.g., "Complete project documentation"
            'description' => $this->faker->paragraph(), // e.g., "This task involves writing the final report..."
            'status' => $this->faker->randomElement(['todo', 'in_progress', 'done']), // Matches your form options
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'), // e.g., "2025-06-15"
        ];
    }

    /**
     * Add a state to include subtasks with the task.
     *
     * @param int $count Number of subtasks to create
     * @return $this
     */
    public function withSubtasks(int $count = 2): self
    {
        return $this->has(
            Subtask::factory()
                ->count($count)
                ->state(function (array $attributes, Task $task) {
                    return ['task_id' => $task->id];
                }),
            'subtasks'
        );
    }
}
