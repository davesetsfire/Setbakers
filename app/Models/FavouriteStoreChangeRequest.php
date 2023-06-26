<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteStoreChangeRequest extends Model {

    use HasFactory;

    protected $fillable = ['favourite_id', 'store_id', 'user_id', 'status', 'request_sent_at'];

}
