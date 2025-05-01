<?php

use App\Models\Task;  // Singular, correct model
use App\Models\Subtask;
use App\Models\User;

beforeEach(function () {
    $this->artisan('migrate', ['--database' => 'sqlite']);
    $this->artisan('session:table');
    $this->artisan('migrate', ['--database' => 'sqlite']);
    $user = User::factory()->create();
    $this->actingAs($user);
});

it('creates a task with subtasks and keeps status as not_done', function () {
    $response = $this->post(route('tasks.store'), [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'todo',
        'due_date' => '2025-05-01',
        'subtasks' => [
            ['title' => 'Subtask 1', 'status' => 'not_done', 'due_date' => '2025-05-06'],
            ['title' => 'Subtask 2', 'status' => 'not_done', 'due_date' => '2025-05-07'],
        ],
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task = Task::first();  // Use Task, not tasks
    expect($task->title)->toBe('Test Task');
    expect($task->status)->toBe('todo');
    expect($task->subtasks)->toHaveCount(2);
});


it('Auto Update Tasks when subtask status is changed to DONE', function () {

    $task = Task::factory()
        ->withSubtasks(2)
        ->create([
            'status' => 'todo',
        ]);
    $subtasks = $task->subtasks;
    foreach ($subtasks as $subtask) {
        $subtask->update(['status' => 'not_done']);
    }
    $response = $this->put(route('tasks.update', $task->id), [
        'title' => 'Updated Task',
        'description' => 'Updated Description',
        'status' => 'todo',
        'due_date' => '2025-05-05',
        'subtasks' => [
            ['id' => $subtasks[0]->id, 'title' => 'Subtask 1', 'status' => 'done', 'due_date' => '2025-05-05'],
            ['id' => $subtasks[1]->id, 'title' => 'Subtask 2', 'status' => 'done', 'due_date' => '2025-05-05'],
        ],
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task->refresh();
    expect($task->status)->toBe('done');
    expect($task->subtasks)->toHaveCount(2);
    expect($task->subtasks->pluck('status')->toArray())->toBe(['done', 'done']);
});
