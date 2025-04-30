<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Traits\UpdatesTaskStatus;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    use UpdatesTaskStatus;
    public function update(request $request, Subtask $subtask)
    {
        $subtask->update($request->all());
        $this->updateTaskStatus($subtask->task_id);
        return redirect()->route('tasks.index')->with('success', 'Subtask updated successfully.');
    }
    public function destroy(Subtask $subtask)
    {
        $taskId = $subtask->task_id;
        $subtask->delete();
        $this->updateTaskStatus($taskId);
        return redirect()->route('tasks.index')->with('success', 'Subtask deleted successfully.');
    }
}
