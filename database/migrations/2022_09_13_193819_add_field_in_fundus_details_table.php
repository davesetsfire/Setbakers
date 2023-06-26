<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInFundusDetailsTable extends Migration {

    public function up() {

        Schema::table('fundus_details', function (Blueprint $table) {
            $table->dropColumn('is_infinite_account');
            $table->enum('package_type', ['basic', 'pro', 'infinite', 'free'])->default('basic')->after('product_upload_limit');
        });
    }

    public function down() {
        Schema::table('fundus_details', function (Blueprint $table) {
            $table->boolean(is_infinite_account)->default(0);
            $table->dropColumn('package_type');
        });
    }

}
