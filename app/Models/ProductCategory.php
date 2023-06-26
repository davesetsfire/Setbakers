<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model {

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

    public function parentcategory() {
        return $this->hasOne('App\Models\ProductCategory', 'id', 'parent_id');
    }

}
