<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $password
 */
class Admin extends Model
{
    use HasApiTokens;
}
