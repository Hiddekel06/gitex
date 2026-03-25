<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'equipe_id');
    }
}
