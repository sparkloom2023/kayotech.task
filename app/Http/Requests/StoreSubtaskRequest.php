<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // Allow authenticated users
    }

    public function rules(): array
    {
        return [
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'task_id.required' => 'The task ID is required.',
            'task_id.exists' => 'The selected task does not exist.',
            'title.required' => 'The subtask title is required.',
            'title.max' => 'The subtask title must not exceed 255 characters.',
            'status.required' => 'The subtask status is required.',
            'status.in' => 'The status must be one of: todo, in_progress, done.',
            'due_date.date' => 'The due date must be a valid date.',
        ];
    }
}
