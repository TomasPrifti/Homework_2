<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'email', 'name', 'address'
    ];
}
