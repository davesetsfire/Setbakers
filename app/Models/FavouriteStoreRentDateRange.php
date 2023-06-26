<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteStoreRentDateRange extends Model {

    use HasFactory;

    public $timestamps = false;
    protected $dates = ['pickup_date', 'return_date'];

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'id', 'favourite_id');
    }

}
