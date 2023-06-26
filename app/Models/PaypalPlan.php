<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalPlan extends Model {

    use HasFactory;

    public function paypalProduct() {
        return $this->hasOne('App\Models\PaypalProduct', 'id', 'product_id');
    }

}
