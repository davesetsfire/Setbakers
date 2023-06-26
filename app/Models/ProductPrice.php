<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['product_id', 'price', 'duration_text'];
    protected $appends = [
        'price_value', 'formatted_price'
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

//    public function getPriceAttribute($price) {
//        return formatNumber($price);
//    }

    public function getPriceValueAttribute() {
        return str_replace('.', '', formatNumber($this->price));
    }
    
    public function getFormattedPriceAttribute() {
        return ($this->price == 0.00) ? '' : formatNumber($this->price);
    }

    public function setPriceAttribute($price) {
        if (!empty($price)) {
            $this->attributes['price'] = strtr($price, [',' => '.', '.' => '']);
        }
    }

}
