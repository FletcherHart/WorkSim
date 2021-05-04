<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccupationRequirement extends Model
{
    use HasFactory;

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }
}
