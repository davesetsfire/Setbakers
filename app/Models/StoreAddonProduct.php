<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreAddonProduct extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['store_id', 'name', 'description', 'replacement_value', 'price', 'image', 'img_width', 'img_height'];
    protected $appends = [
        'replacement_amount_value', 'formatted_replacement_value', 'price_value', 'formatted_price'
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

    public function getReplacementAmountValueAttribute() {
        return ($this->replacement_value == 0.00) ? '' : str_replace('.', '', formatNumber($this->replacement_value));
    }

    public function setReplacementValueAttribute($value) {
        if (!empty($value)) {
            $this->attributes['replacement_value'] = strtr($value, [',' => '.', '.' => '']);
        } else {
            $this->attributes['replacement_value'] = 0;
        }
    }

    public function getFormattedReplacementValueAttribute() {
        return ($this->replacement_value == 0.00) ? '' : formatNumber($this->replacement_value);
    }

    public function getPriceValueAttribute() {
        if (!empty($this->price)) {
            return str_replace('.', '', formatNumber($this->price));
        } else {
            return $this->price;
        }
    }

    public function getFormattedPriceAttribute() {
        if (!empty($this->price)) {
            return ($this->price == 0.00) ? '' : formatNumber($this->price);
        } else {
            return $this->price;
        }
    }

    public function setPriceAttribute($price) {
        if (!empty($price)) {
            $this->attributes['price'] = strtr($price, [',' => '.', '.' => '']);
        }
    }

}
