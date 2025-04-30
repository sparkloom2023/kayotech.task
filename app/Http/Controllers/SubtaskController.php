<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function update(request $request, Subtask $subtask)
    {
        $subtask->update($request->all());
        return redirect()->route('tasks.index')->with('success', 'Subtask updated successfully.');
    }
    public function destroy(Subtask $subtask)
    {
        $subtask->delete();

        return redirect()->route('tasks.index')->with('success', 'Subtask deleted successfully.');
    }
}
