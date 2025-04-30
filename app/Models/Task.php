<?php

namespace App\Models;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
    ];
    protected $casts = [
        'due_date' => 'date',
    ];
    public function subtasks() // Renamed from "task" to "subtasks"
    {
        return $this->hasMany(Subtask::class, 'task_id');
    }
}
