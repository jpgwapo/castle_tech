<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'task_name', 'task_description'
    ];

    /**
     * Get the sub task that is connected to the task.
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
