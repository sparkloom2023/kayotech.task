<?php

use App\Models\Task;  // Singular, correct model
use App\Models\Subtask;

beforeEach(function () {
    // Run migrations for the in-memory SQLite database
    $this->artisan('migrate', ['--database' => 'sqlite']);
    // Ensure the session table is created since SESSION_DRIVER=database
    $this->artisan('session:table');
    $this->artisan('migrate', ['--database' => 'sqlite']);
});

it('creates a task with subtasks and keeps status as not_done', function () {
    $response = $this->post(route('tasks.store'), [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
        'subtasks' => [
            ['title' => 'Subtask 1', 'status' => 'not_done', 'due_date' => '2025-05-01'],
            ['title' => 'Subtask 2', 'status' => 'not_done', 'due_date' => '2025-05-02'],
        ],
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task = Task::first();  // Use Task, not tasks
    expect($task->title)->toBe('Test Task');
    expect($task->status)->toBe('not_done');
    expect($task->subtasks)->toHaveCount(2);
});

// Update all other tests similarly
it('updates task status to done when all subtasks are done via TaskController', function () {
    $task = Task::factory()->create([  // Use Task, not tasks
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
    ]);
    $task->subtasks()->create(['title' => 'Subtask 1', 'status' => 'not_done', 'due_date' => '2025-05-01']);
    $task->subtasks()->create(['title' => 'Subtask 2', 'status' => 'not_done', 'due_date' => '2025-05-02']);

    $response = $this->put(route('tasks.update', $task->id), [
        'title' => 'Updated Task',
        'description' => 'Updated Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
        'subtasks' => [
            ['title' => 'Subtask 1', 'status' => 'done', 'due_date' => '2025-05-01'],
            ['title' => 'Subtask 2', 'status' => 'done', 'due_date' => '2025-05-02'],
        ],
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task->refresh();
    expect($task->status)->toBe('done');
    expect($task->subtasks)->toHaveCount(2);
    expect($task->subtasks->pluck('status')->toArray())->toBe(['done', 'done']);
});

// Apply this fix (Task:: instead of tasks::) to all remaining tests...
