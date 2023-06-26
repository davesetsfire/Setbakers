<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImpression extends Model {

    use HasFactory;

    protected $fillable = ['user_id', 'key_name', 'key_value'];

}
