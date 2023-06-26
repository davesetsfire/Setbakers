<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['user_id', 'subscription_id', 'order_number',
        'paypal_order_id', 'paypal_subscription_id', 'order_date', 'amount',
        'currency', 'payment_mode', 'status', 'created_by', 'updated_by'];

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

    public function subscription() {
        return $this->hasOne('App\Models\SubscriptionPlan', 'id', 'subscription_id');
    }

}
