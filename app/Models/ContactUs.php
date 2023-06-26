<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['user_id', 'first_name', 'last_name', 'email', 'phone_number', 'message'];

}
