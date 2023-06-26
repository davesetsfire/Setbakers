<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 40)->default('')->unique();
            $table->string('name', 190)->default('')->index();
            $table->string('slug', 190)->default('')->unique();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('image', 255)->default('');
            $table->decimal('regular_price', 8, 2)->default(0.00);
            $table->decimal('sale_price', 8, 2)->default(0.00);
            $table->boolean('custom_price_available')->default(0);
            $table->bigInteger('manufacturer_id')->default(0);
            $table->bigInteger('store_id')->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('discount', 2, 2)->default(0.00);
            $table->bigInteger('display_order')->default(0);
            $table->bigInteger('group_id')->default(0);
            $table->bigInteger('download_counts')->default(0);
            $table->bigInteger('like_counts')->default(0);
            $table->integer('color_id')->default(0);
            $table->integer('epoche')->default(0);
            $table->integer('year')->default(9999);
            $table->integer('style_id')->default(0);
            $table->enum('location_at', ['', 'fundus', 'others'])->default('');
            $table->string('location', 255)->default('');
            $table->geometry('geo_location')->nullable();
            $table->integer('postal_code')->default(0);
            $table->enum('stock_status', ['instock', 'outofstock'])->default('instock');
            $table->decimal('shipping_charges', 8, 2)->default(0.00);
            $table->decimal('tax_percentage', 8, 2)->default(0.00);
            $table->decimal('weight', 8, 2)->default(0.00);
            $table->decimal('length', 8, 2)->default(0.00);
            $table->decimal('width', 8, 2)->default(0.00);
            $table->decimal('height', 8, 2)->default(0.00);
            $table->boolean('is_premium')->default(0);
            $table->boolean('is_active')->default(0);
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        
        DB::statement('ALTER TABLE cm_products ADD FULLTEXT(keywords)');
        //DB::statement('ALTER TABLE cm_products ADD SPATIAL INDEX(geo_location)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('products');
    }

}
