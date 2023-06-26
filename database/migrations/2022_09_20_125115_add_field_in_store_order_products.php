<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInStoreOrderProducts extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('store_order_products', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->default(0.00)->after('quantity');
            $table->unsignedBigInteger('addon_product_id')->default(0)->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('store_order_products', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'addon_product_id']);
        });
    }

}
