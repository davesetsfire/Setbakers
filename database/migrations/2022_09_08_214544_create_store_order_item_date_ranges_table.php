<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StoreOrderItem;
use App\Models\StoreOrderItemDateRange;

class CreateStoreOrderItemDateRangesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('store_order_item_date_ranges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_item_id');
            $table->foreign('order_item_id')->references('id')->on('store_order_items');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('store_orders');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $storeOrderItems = StoreOrderItem::get();
        foreach ($storeOrderItems as $storeOrderItem) {
            StoreOrderItemDateRange::create([
                'order_item_id' => $storeOrderItem->id,
                'order_id' => $storeOrderItem->order_id,
                'start_date' => $storeOrderItem->rent_start_date,
                'end_date' => $storeOrderItem->rent_end_date,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('store_order_item_date_ranges');
    }

}
