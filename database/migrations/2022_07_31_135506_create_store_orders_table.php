<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('store_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number', 25)->default('');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('project_details');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('fundus_details');
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('project_id');
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('store_orders');
    }

}
