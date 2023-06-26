<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundusDowngradeRequest extends Model {

    use HasFactory;

    protected $fillable = ['fundus_id', 'current_package', 'new_package', 'start_date', 'end_date', 'status'];

}
