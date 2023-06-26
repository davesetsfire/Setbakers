<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFieldsProductsTable extends Migration {

    public function up() {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('img_width', 8, 2)->default(0.00)->after('image');
            $table->decimal('img_height', 8, 2)->default(0.00)->after('img_width');
            $table->enum('dimension_unit', ['mm', 'cm', 'm'])->default('mm')->after('height');
        });

        DB::statement('update cm_products set img_width=width, img_height=height, width=0.00, height=0.00');
    }

    public function down() {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'img_width',
                'img_height',
                'dimension_unit'
            ]);
        });
    }

}
