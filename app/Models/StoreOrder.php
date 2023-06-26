<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrder extends Model {

    use HasFactory,
        SoftDeletes;

    protected $dates = ['created_at'];
    protected $fillable = ['order_number', 'project_id', 'store_id'];

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

    public function project() {
        return $this->hasOne('App\Models\ProjectDetail', 'id', 'project_id');
    }

    public function orderItems() {
        return $this->hasMany('App\Models\StoreOrderItem', 'order_id', 'id');
    }

}
