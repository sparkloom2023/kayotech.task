<?php

namespace App\Traits;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\tasks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait UpdatesTaskStatus
{
    protected function updateTaskStatus($taskId)
    {
        DB::transaction(function () use ($taskId) {
            // Fetch the task with optimistic locking
            $task = Task::lockForUpdate()->findOrFail($taskId);

            // Check if all subtasks are "done"
            $notDoneCount = Subtask::where('task_id', $taskId)
                ->where('status', '!=', 'done')
                ->count();

            if ($notDoneCount === 0) {
                // All subtasks are done, set task to "done"
                $task->status = 'done';
                $task->save();

                Log::info("Task #{$taskId} automatically set to 'done' because all subtasks are completed.");
            }
        });
    }
}
