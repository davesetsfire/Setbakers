<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'keywords', 'slug', 'image', 'img_width', 'img_height', 'epoche', 'year',
        'quantity', 'color_id', 'style_id', 'replacement_value', 'length',
        'width', 'height', 'dimension_unit', 'graphic_form', 'file_format', 'copy_right',
        'manufacturer_id', 'manufacture_country', 'location_at', 'location', 'postal_code', 'is_active', 'store_id', 'created_by', 'updated_by'];
    protected $dates = ['deleted_at'];
    protected $appends = [
        'replacement_amount_value', 'formatted_replacement_value', 'length_value', 'width_value', 'height_value'
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

    public function getYearAttribute($year) {
        return ($year == 9999) ? '' : $year;
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

    public function getLengthValueAttribute() {
        return ($this->length == 0.00) ? '' : str_replace('.', '', formatNumber($this->length));
    }

    public function setLengthAttribute($value) {
        if (!empty($value)) {
            $this->attributes['length'] = strtr($value, [',' => '.', '.' => '']);
        } else {
            $this->attributes['length'] = 0;
        }
    }

    public function getWidthValueAttribute() {
        return ($this->width == 0.00) ? '' : str_replace('.', '', formatNumber($this->width));
    }

    public function setWidthAttribute($value) {
        if (!empty($value)) {
            $this->attributes['width'] = strtr($value, [',' => '.', '.' => '']);
        } else {
            $this->attributes['width'] = 0;
        }
    }

    public function getHeightValueAttribute() {
        return ($this->height == 0.00) ? '' : str_replace('.', '', formatNumber($this->height));
    }

    public function setHeightAttribute($value) {
        if (!empty($value)) {
            $this->attributes['height'] = strtr($value, [',' => '.', '.' => '']);
        } else {
            $this->attributes['height'] = 0;
        }
    }

    public function setYearAttribute($year) {
        $this->attributes['year'] = ($year == '') ? 9999 : $year;
    }

    public function scopeActive($query) {
        return $query->where('is_active', 1);
    }

    public function getCategoryNameAttribute() {
        $level1Category = $this->productcategory[0]['parentcategory']['parentcategory']['name'] ?? '';
        $level2Category = $this->productcategory[0]['parentcategory']['name'] ?? '';
        $level3Category = $this->productcategory[0]['name'] ?? '';
        $categoryName = "";
        if (!empty($level1Category)) {
            $categoryName .= $level1Category;
        }
        if (!empty($level2Category)) {
            $categoryName .= !empty($level1Category) ? ' / ' . $level2Category : $level2Category;
        }
        if (!empty($level3Category)) {
            $categoryName .= !empty($level2Category) ? ' / ' . $level3Category : $level3Category;
        }

        return $categoryName;
    }

    public function getTopCategorySlugAttribute() {
        return $this->productcategory[0]['parentcategory']['parentcategory']['slug'] ?? $this->productcategory[0]['parentcategory']['slug'] ?? '';
    }

    public function getDimensionsAttribute() {
        if ($this->length > 0 || $this->width > 0 || $this->height > 0) {
            return formatNumber($this->length) . 'x' . formatNumber($this->width) . 'x' . formatNumber($this->height) . ' ' . $this->dimension_unit;
        } else {
            return '';
        }
    }

    public function productcategorymapping() {
        return $this->hasMany('App\Models\ProductCategoryMapping');
    }

    public function productcategory() {
        return $this->belongsToMany('App\Models\ProductCategory', 'product_category_mappings', 'product_id', 'category_id');
    }

    public function productMedia() {
        return $this->hasMany('App\Models\ProductMedia', 'product_id', 'id');
    }

    public function fundusDetail() {
        return $this->hasOne('App\Models\FundusDetail', 'id', 'store_id');
    }

    public function color() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'color_id');
    }

    public function prices() {
        return $this->hasMany('App\Models\ProductPrice', 'product_id', 'id')->orderBy('price');
    }

    public function epocheText() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'epoche');
    }

    public function style() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'style_id');
    }

    public function bookmark() {
        return $this->hasOne('App\Models\FavouriteItem', 'product_id', 'id')
                        ->where('favourite_id', 1)
                        ->where('user_id', \Auth::user()->id ?? 0);
    }

    public function graphicForm() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'graphic_form');
    }

    public function manufacture() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'manufacturer_id');
    }

    public function manufactureCountry() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'manufacture_country');
    }

    public function fileFormat() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'file_format');
    }

    public function copyright() {
        return $this->hasOne('App\Models\AttributeOption', 'id', 'copy_right');
    }

}
