<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

    public function occupationRequirements()
    {
        return $this->hasMany(OccupationRequirement::class);
    }
}
