<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FavouriteStoreChangeRequest;

class Favourite extends Model {

    use HasFactory,
        SoftDeletes;

    protected $dates = ['start_date', 'end_date'];
    protected $fillable = ['user_id', 'project_id', 'name', 'start_date', 'end_date', 'request_to_store'];

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \Auth::user()->id ?? 0;
            $model->updated_by = \Auth::user()->id ?? 0;
        });

        static::updating(function ($model) {
            $model->updated_by = \Auth::user()->id ?? 0;
        });

//        static::updated(function ($model) {
//            \Log::info('Favourite Update Event:' . $model);
//            $userId = \Auth::user()->id ?? 0;
//            if ($userId > 0) {
//                FavouriteStoreChangeRequest::where('favourite_id', $model->id)
//                        ->where('user_id', $userId)
//                        ->where('status', 1)
//                        ->update(['status' => 2]);
//            }
//        });
    }

    public function favouriteItems() {
        return $this->hasMany('App\Models\FavouriteItem', 'favourite_id', 'id');
    }

    public function favouriteDateRanges() {
        return $this->hasMany('App\Models\FavouriteDateRange', 'favourite_id', 'id');
    }

}
