<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Administrateur extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'administrateurs';

    protected $fillable = ['email', 'password'];

    protected $hidden = ['password'];
}
