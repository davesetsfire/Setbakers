<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FavouriteStoreChangeRequest;

class FavouriteDateRange extends Model {

    use HasFactory;

    protected $dates = ['start_date', 'end_date'];
    protected $fillable = ['favourite_id', 'start_date', 'end_date'];

    public static function boot() {

        parent::boot();

//        static::updated(function ($model) {
//            $userId = \Auth::user()->id ?? 0;
//            if ($userId > 0) {
//                FavouriteStoreChangeRequest::where('favourite_id', $model->favourite_id)
//                        ->where('user_id', $userId)
//                        ->where('status', 1)
//                        ->update(['status' => 2]);
//            }
//        });

        static::created(function ($model) {
            \Log::info('FavouriteDateRange Create Event:' . $model);
            $userId = \Auth::user()->id ?? 0;
            if ($userId > 0) {
                FavouriteStoreChangeRequest::where('favourite_id', $model->favourite_id)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->update(['status' => 2]);
            }
        });

        static::deleted(function ($model) {
            \Log::info('FavouriteDateRange Delete Event:' . $model);
            $userId = \Auth::user()->id ?? 0;
            if ($userId > 0) {
                FavouriteStoreChangeRequest::where('favourite_id', $model->favourite_id)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->update(['status' => 2]);
            }
        });
    }

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'id', 'favourite_id');
    }

    public function favouriteStoreRentDateRange() {
        return $this->hasMany('App\Models\FavouriteStoreRentDateRange', 'favourite_date_range_id', 'id');
    }

}
