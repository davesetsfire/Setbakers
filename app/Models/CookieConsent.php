<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model {

    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'user_action',
        'analyse',
        'marketing',
        'ip'
    ];

}
