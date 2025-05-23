<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\StoreTasksRequest;
use App\Models\Subtask;
use App\Models\Task;

use App\Traits\UpdatesTaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TaskController extends Controller
{
    use UpdatesTaskStatus;
    public function index()
    {
        $tasks = Task::with('subtasks')->get()->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'subtasks' => $task->subtasks ? $task->subtasks->map(function ($subtask) {
                    return [
                        'id' => $subtask->id,
                        'title' => $subtask->title,
                        'description' => $subtask->description,
                        'status' => $subtask->status,
                        'due_date' => $subtask->due_date ? $subtask->due_date->format('Y-m-d') : null,
                    ];
                })->toArray() : [],
            ];
        })->toArray();
        // Log::info('Tasks data are visible in front:', $tasks);
        // dd($tasks);
        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }
    public function store(StoreTasksRequest $request)
    {

        $validated = $request->validated();

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ];

        $task = Task::create($taskData);

        if ($request->has('subtasks') && is_array($request->input('subtasks'))) {
            foreach ($request->input('subtasks') as $subtaskData) {
                if (!empty($subtaskData['title'])) {
                    $task->subtasks()->create([
                        'title' => $subtaskData['title'],
                        'status' => $subtaskData['status'] ?? 'todo',
                        'due_date' => $subtaskData['due_date'] ?? null,
                    ]);
                }
            }
        }
        $this->updateTaskStatus($task->id);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function update(StoreTasksRequest $request, Task $task)
    {
       $validated = $request->validated();

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ];

        $task->update($taskData);

        if ($request->has('subtasks') && is_array($request->input('subtasks'))) {
            $task->subtasks()->delete();
            foreach ($request->input('subtasks') as $subtaskData) {
                if (!empty($subtaskData['title'])) {
                    $task->subtasks()->create([
                        'title' => $subtaskData['title'],
                        'status' => $subtaskData['status'] ?? 'todo',
                        'due_date' => $subtaskData['due_date'] ?? null,
                    ]);
                }
            }
        }
        $this->updateTaskStatus($task->id);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
