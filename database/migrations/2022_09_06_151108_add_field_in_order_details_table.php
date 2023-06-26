<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInOrderDetailsTable extends Migration {

    public function up() {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('paypal_order_id', 100)->default('')->after('order_number');
            $table->string('paypal_subscription_id', 100)->default('')->after('paypal_order_id');
        });
    }

    public function down() {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['paypal_order_id', 'paypal_subscription_id']);
        });
    }

}
