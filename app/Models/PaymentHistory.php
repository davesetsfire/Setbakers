<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model {

    use HasFactory;

    protected $table = "payment_history";
    protected $fillable = ['user_id', 'subscription_id', 'order_id', 'amount',
        'currency', 'request', 'response', 'payment_mode', 'status',
        'created_by', 'updated_by'];

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

}
