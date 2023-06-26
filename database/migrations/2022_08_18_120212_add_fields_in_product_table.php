<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInProductTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('replacement_value', 8, 2)->nullable()->after('style_id');
            $table->integer('graphic_form')->default(0)->after('replacement_value');
            $table->integer('file_format')->default(0)->after('graphic_form');
            $table->integer('copy_right')->default(0)->after('file_format');
            $table->integer('manufacture_country')->default(0)->after('copy_right');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'replacement_value',
                'graphic_form',
                'file_format',
                'copy_right',
                'manufacture_country'
            ]);
        });
    }

}
