<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   protected $fillable = [
        'creator_id','assignee_id','title','description',
        'priority','deadline','status'
    ];

    public function creator() { 
        return $this->belongsTo(User::class,'creator_id'); 
    }
    public function assignee() {
        return $this->belongsTo(User::class,'assignee_id');
    }
    public function documents() {
        return $this->hasMany(TaskDocument::class); 
    }

}
