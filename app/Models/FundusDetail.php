<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundusDetail extends Model {

    use HasFactory,
        SoftDeletes;

    protected $dates = ['paused_till_date'];
    protected $fillable = ['user_id', 'fundus_name', 'fundus_email', 'fundus_phone', 'owner_first_name',
        'owner_last_name', 'description', 'company_name', 'ust_id', 'is_company', 'website', 'house_number',
        'street', 'postal_code', 'location', 'geo_location', 'country', 'logo_image_path',
        'subscription_id', 'subscription_start_date', 'subscription_end_date', 'is_subscription_paused',
        'product_upload_limit', 'package_type'];
    protected $appends = [
        'fundus_owner_name',
    ];

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

    public function getFundusOwnerNameAttribute() {
        return $this->owner_first_name . ' ' . $this->owner_last_name;
    }

    public function subscription() {
        return $this->hasOne('App\Models\SubscriptionPlan', 'id', 'subscription_id');
    }

}
