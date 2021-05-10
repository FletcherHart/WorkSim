<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegreeProgress extends Model
{
    use HasFactory;

    protected $table = "degree_progress";
    protected $fillable = ['user_id', 'degree_id', 'progress'];
}
