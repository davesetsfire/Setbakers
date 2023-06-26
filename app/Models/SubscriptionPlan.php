<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model {

    use HasFactory,
        SoftDeletes;

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

    public function scopeActive($query) {
        return $query->where('is_active', 1);
    }

    public function scopeCurrent($query) {
        return $query->where('is_current', 1);
    }

    public function scopeProject($query) {
        return $query->where('account_type', 'project');
    }

    public function scopeFundus($query) {
        return $query->where('account_type', 'fundus');
    }

    public function paypalPlan() {
        return $this->hasOne('App\Models\PaypalPlan', 'plan_code', 'paypal_plan_id');
    }

}
