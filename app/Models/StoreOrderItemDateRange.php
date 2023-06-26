<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrderItemDateRange extends Model {

    use HasFactory,
        SoftDeletes;

    protected $dates = ['start_date', 'end_date'];
    protected $fillable = ['order_item_id', 'order_id', 'start_date', 'end_date'];

}
