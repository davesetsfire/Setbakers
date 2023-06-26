<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FavouriteStoreChangeRequest;

class FavouriteItem extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['user_id', 'favourite_id', 'store_id', 'product_id', 'requested_count', 'available_count'];

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \Auth::user()->id ?? 0;
            $model->updated_by = \Auth::user()->id ?? 0;
        });

        static::updating(function ($model) {
            $model->updated_by = \Auth::user()->id ?? 0;
        });

        static::created(function ($model) {
            \Log::info('FavouriteItem Create Event:' . $model);
            $userId = \Auth::user()->id ?? 0;
            if ($userId > 0) {
                FavouriteStoreChangeRequest::where('favourite_id', $model->favourite_id)
                        ->where('store_id', $model->store_id)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->update(['status' => 2]);
            }
        });

        static::updated(function ($model) {
            \Log::info('FavouriteItem Update Event:' . $model);
            $userId = \Auth::user()->id ?? 0;
            if ($userId > 0) {
                FavouriteStoreChangeRequest::where('favourite_id', $model->favourite_id)
                        ->where('store_id', $model->store_id)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->update(['status' => 2]);
            }
        });

        static::deleted(function ($model) {
            $userId = \Auth::user()->id ?? 0;
            if ($userId > 0) {
                FavouriteStoreChangeRequest::where('favourite_id', $model->favourite_id)
                        ->where('store_id', $model->store_id)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->update(['status' => 2]);
            }
        });
    }

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'id', 'favourite_id');
    }

    public function store() {
        return $this->hasOne('App\Models\FundusDetail', 'id', 'store_id');
    }

}
