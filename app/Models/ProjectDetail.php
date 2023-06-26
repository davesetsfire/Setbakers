<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDetail extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['user_id', 'project_name', 'description', 'company_name', 'ust_id', 'is_company',
        'house_number', 'street', 'postal_code', 'location', 'country',
        'subscription_id', 'subscription_start_date', 'subscription_end_date', 'is_subscription_paused'];

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

    public function getAddressLineOneAttribute() {
        return $this->street . ' ' . $this->house_number;
    }

    public function getAddressLineTwoAttribute() {
        return $this->postal_code . ' ' . $this->location;
    }

    public function subscription() {
        return $this->hasOne('App\Models\SubscriptionPlan', 'id', 'subscription_id');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

}
