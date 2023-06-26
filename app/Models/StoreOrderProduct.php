<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrderProduct extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['order_item_id', 'order_id', 'product_id', 'quantity', 'unit_price', 'addon_product_id'];
    protected $appends = [
        'unit_price_value', 'formatted_unit_price'
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

    public function getUnitPriceValueAttribute() {
        return ($this->unit_price == 0.00) ? '' : str_replace('.', '', formatNumber($this->unit_price));
    }

    public function setUnitPriceAttribute($value) {
        if (!empty($value)) {
            $this->attributes['unit_price'] = strtr($value, [',' => '.', '.' => '']);
        } else {
            $this->attributes['unit_price'] = 0;
        }
    }

    public function getFormattedUnitPriceAttribute() {
        return ($this->unit_price == 0.00) ? '' : formatNumber($this->unit_price);
    }

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function order() {
        return $this->hasOne('App\Models\StoreOrder', 'id', 'order_id');
    }

    public function addonProduct() {
        return $this->hasOne('App\Models\StoreAddonProduct', 'id', 'addon_product_id');
    }

}
