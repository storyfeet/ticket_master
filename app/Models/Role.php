<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
* Role exists to check with a person is admin or not,
* It may later be used to check wether a user might fulfil other roles.
*/
class Role extends Model
{
    use HasFactory;
    //
}
