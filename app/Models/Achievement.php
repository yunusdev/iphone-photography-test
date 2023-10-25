<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    const LESSON = 'Lesson';
    const COMMENT = 'Comment';

    protected $fillable = ['name', 'group', 'number'];
}
