<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInFundusDetailsTable extends Migration {

    public function up() {
        Schema::table('fundus_details', function (Blueprint $table) {
            $table->bigInteger('subscription_id')->default(0)->after('paypal_subscription_id');
            $table->boolean('is_subscription_paused')->default(0)->after('subscription_id');
            $table->dateTime('subscription_start_date')->nullable()->after('is_subscription_paused');
            $table->dateTime('subscription_end_date')->nullable()->after('subscription_start_date');
            $table->integer('product_upload_limit')->default(100)->after('subscription_end_date');
            $table->boolean('is_infinite_account')->default(0)->after('product_upload_limit');
        });
    }

    public function down() {
        Schema::table('fundus_details', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_id',
                'is_subscription_paused',
                'subscription_start_date',
                'subscription_end_date',
                'product_upload_limit',
                'is_infinite_account'
            ]);
        });
    }

}
