<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDocument extends Model
{
    protected $fillable = ['task_id','filename','original_name','mime','size','uploaded_by'];

    public function task() { 
        return $this->belongsTo(Task::class); 
    }


}
