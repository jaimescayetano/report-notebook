<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'project_id',
        'end_date'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }
}
