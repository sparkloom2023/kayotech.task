<?php

use App\Models\Task;
use App\Models\Subtask;
use App\Models\tasks;

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
    $task = Task::first();
    expect($task->title)->toBe('Test Task');
    expect($task->status)->toBe('not_done');
    expect($task->subtasks)->toHaveCount(2);
});

it('updates task status to done when all subtasks are done via TaskController', function () {
    $task = Task::factory()->create([
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

it('updates task status to done when all subtasks are done via SubtaskController', function () {
    $task = Task::factory()->create([
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
    ]);
    $subtask1 = $task->subtasks()->create(['title' => 'Subtask 1', 'status' => 'not_done', 'due_date' => '2025-05-01']);
    $subtask2 = $task->subtasks()->create(['title' => 'Subtask 2', 'status' => 'done', 'due_date' => '2025-05-02']);

    $response = $this->put(route('subtasks.update', $subtask1->id), [
        'title' => 'Subtask 1 Updated',
        'status' => 'done',
        'due_date' => '2025-05-01',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task->refresh();
    expect($task->status)->toBe('done');
    expect($subtask1->refresh()->status)->toBe('done');
});

it('does not set task status to done if not all subtasks are done', function () {
    $task = Task::factory()->create([
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
    ]);
    $subtask1 = $task->subtasks()->create(['title' => 'Subtask 1', 'status' => 'not_done', 'due_date' => '2025-05-01']);
    $task->subtasks()->create(['title' => 'Subtask 2', 'status' => 'done', 'due_date' => '2025-05-02']);

    $response = $this->put(route('subtasks.update', $subtask1->id), [
        'title' => 'Subtask 1 Updated',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task->refresh();
    expect($task->status)->toBe('not_done');
    expect($subtask1->refresh()->status)->toBe('not_done');
});

it('handles a task with no subtasks without breaking', function () {
    $task = Task::factory()->create([
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
    ]);

    $response = $this->put(route('tasks.update', $task->id), [
        'title' => 'Updated Task',
        'description' => 'Updated Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
        'subtasks' => [],
    ]);

    $response->assertRedirect(route('tasks.index'));
    $task->refresh();
    expect($task->status)->toBe('not_done');
    expect($task->subtasks)->toHaveCount(0);
});

it('fails to create a task without a title', function () {
    $response = $this->post(route('tasks.store'), [
        'title' => '',
        'description' => 'Test Description',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
        'subtasks' => [
            ['title' => 'Subtask 1', 'status' => 'not_done', 'due_date' => '2025-05-01'],
        ],
    ]);

    $response->assertSessionHasErrors('title');
    expect(Task::count())->toBe(0);
});
