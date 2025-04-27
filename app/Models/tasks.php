<?php

namespace App\Models;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class tasks extends Model
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
}
