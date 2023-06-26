<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategoryMapping extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['product_id', 'category_id'];
    public $timestamps = false;

}
