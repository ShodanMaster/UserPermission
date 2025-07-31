<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
