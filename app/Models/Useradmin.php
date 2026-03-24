<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Useradmin extends Model
{
    //
        protected $fillable = [
            'name',
            'email',
            'password',
        ];
}
