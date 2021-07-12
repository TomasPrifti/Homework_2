<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'email', 'name', 'address', 'description'
    ];

    public function favorites() {
        return $this->belongsToMany("App\Models\Favorite");
    }
}
