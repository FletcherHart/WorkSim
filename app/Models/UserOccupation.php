<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOccupation extends Model
{
    use HasFactory;

    protected $table = "user_occupation";
    protected $fillable = [
        'user_id',
        'occupation_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function occupation() {
        return $this->hasOne(Occupation::class);
    }
}
