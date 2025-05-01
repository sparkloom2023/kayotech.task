<?php

use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;

beforeEach(function () {
    $this->artisan('migrate', ['--database' => 'sqlite']);
    $this->artisan('session:table');
    $this->artisan('migrate', ['--database' => 'sqlite']);
    $user = User::factory()->create();
    $this->actingAs($user);
});

it('updates a subtask and redirects to tasks index', function () {
    $task = Task::factory()->create(['status' => 'todo']);
    $subtask = Subtask::factory()->create([
        'task_id' => $task->id,
        'title' => 'Old Subtask Title',
        'status' => 'not_done',
        'due_date' => '2025-05-01',
    ]);

    $response = $this->put(route('subtasks.update', $subtask->id), [
        'title' => 'Updated Subtask Title',
        'status' => 'done',
        'due_date' => '2025-05-02',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success', 'Subtask updated successfully.');

    $subtask->refresh();
    expect($subtask->title)->toBe('Updated Subtask Title');
    expect($subtask->status)->toBe('done');
    expect($subtask->due_date->format('Y-m-d'))->toBe('2025-05-02'); // Fixed: Convert Carbon to string
});
it('updates task status to done when all subtasks are done via SubtaskController', function () {

    $task = Task::factory()->create(['status' => 'todo']);
    $subtask1 = Subtask::factory()->create([
        'task_id' => $task->id,
        'status' => 'not_done',
    ]);
    $subtask2 = Subtask::factory()->create([
        'task_id' => $task->id,
        'status' => 'done',
    ]);

    $response = $this->put(route('subtasks.update', $subtask1->id), [
        'title' => 'Updated Subtask 1',
        'status' => 'done',
        'due_date' => '2025-05-01',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success', 'Subtask updated successfully.');

    $task->refresh();
    expect($task->status)->toBe('done');

    $subtask1->refresh();
    expect($subtask1->status)->toBe('done');
});
it('deletes a subtask and redirects to tasks index', function () {
    $task = Task::factory()->create(['status' => 'todo']);
    $subtask = Subtask::factory()->create([
        'task_id' => $task->id,
        'status' => 'not_done',
    ]);
    $response = $this->delete(route('subtasks.destroy', $subtask->id));
    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('success', 'Subtask deleted successfully.');

    expect(Subtask::find($subtask->id))->toBeNull();
});
