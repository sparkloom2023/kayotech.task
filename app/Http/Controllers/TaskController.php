<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\StoreTasksRequest;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\tasks;
use App\Traits\UpdatesTaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TaskController extends Controller
{
    use UpdatesTaskStatus;
    public function index()
    {
        $tasks = Tasks::with('subtasks')->get()->map(function ($task) {
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
    // public function store(request $request)
    // {
    //     // dd($request->all());
    //     $task = Tasks::create($request->all());
    //     if ($request->has('subtasks') && is_array($request->input('subtasks'))) {
    //         foreach ($request->input('subtasks') as $subtaskData) {
    //             if (!empty($subtaskData['title'])) {
    //                 $task->subtasks()->create([
    //                     'title' => $subtaskData['title'],
    //                     'status' => $subtaskData['status'] ?? 'todo',
    //                     'description' => $subtaskData['description'] ?? null,
    //                     'due_date' => $subtaskData['due_date'] ?? null,
    //                 ]);
    //             }
    //         }
    //     }
    //     return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    // }

    public function store(Request $request)
    {
        // Basic validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.status' => 'required|in:not_done,done',
            'subtasks.*.due_date' => 'nullable|date',
        ]);

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ];

        $task = Tasks::create($taskData);

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

        // Check if all subtasks are "done" and update task status
        $this->updateTaskStatus($task->id);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }
    // public function update(request $request, tasks $task)
    // {
    //     $task->update($request->all());
    //     if ($request->has('subtasks') && is_array($request->input('subtasks'))) {
    //         $task->subtasks()->delete();
    //         foreach ($request->input('subtasks') as $subtaskData) {
    //             if (!empty($subtaskData['title'])) {
    //                 $task->subtasks()->create([
    //                     'title' => $subtaskData['title'],
    //                     'status' => $subtaskData['status'] ?? 'todo',
    //                     'due_date' => $subtaskData['due_date'] ?? null,
    //                 ]);
    //             }
    //         }
    //     }
    //     return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    // }

    public function update(Request $request, Tasks $task)
    {
        // Basic validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.status' => 'required|in:not_done,done',
            'subtasks.*.due_date' => 'nullable|date',
        ]);

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

        // Check if all subtasks are "done" and update task status
        $this->updateTaskStatus($task->id);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
    public function destroy(Tasks $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
