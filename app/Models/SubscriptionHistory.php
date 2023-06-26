<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionHistory extends Model {

    use HasFactory,
        SoftDeletes;

    protected $table = "subscription_history";
    protected $fillable = ['user_id', 'subscription_id', 'amount', 'currency', 'start_date', 'end_date'];

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
