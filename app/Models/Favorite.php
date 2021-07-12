<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'cost'
    ];

    public function restaurants() {
        return $this->belongsToMany("App\Models\Restaurant");
    }
}
