<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrderItem extends Model {

    use HasFactory,
        SoftDeletes;

    protected $dates = ['rent_start_date', 'rent_end_date'];
    protected $fillable = ['order_id', 'favourite_id', 'rent_start_date', 'rent_end_date', 'favourite_name'];

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \Auth::user()->id ?? 0;
            $model->updated_by = \Auth::user()->id ?? 0;
        });

        static::updating(function ($model) {
            $model->updated_by = \Auth::user()->id ?? 0;
        });
    }

    public function orderProducts() {
        return $this->hasMany('App\Models\StoreOrderProduct', 'order_item_id', 'id');
    }

    public function dateRanges() {
        return $this->hasMany('App\Models\StoreOrderItemDateRange', 'order_item_id', 'id');
    }

    public function order() {
        return $this->hasOne('App\Models\StoreOrder', 'id', 'order_id');
    }

}
