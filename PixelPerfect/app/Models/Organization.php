<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'vat',
        'phone',
        'email' // Include this if you want to mass assign description as well
    ];
}
