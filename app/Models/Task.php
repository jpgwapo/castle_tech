<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $softDelete = true;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'task_name', 'task_description', 'status'
    ];

    /**
     * Get the user name that owns the task.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the task for the sub task.
     */
    public function subtask()
    {
        return $this->hasMany('App\Models\Subtask', 'task_id');
    }
}
