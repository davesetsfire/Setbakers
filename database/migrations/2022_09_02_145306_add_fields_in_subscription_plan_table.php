<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInSubscriptionPlanTable extends Migration {

    public function up() {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->enum('account_type', ['project', 'fundus'])->default('project')->after('name');
            $table->decimal('basic_amount', 8, 2)->default(0.00)->after('account_type');
            $table->decimal('tax', 8, 2)->default(0.00)->after('basic_amount');
            $table->string('paypal_plan_id', 100)->default('')->after('valid_to');
            $table->string('paypal_trial_plan_id', 100)->nullable()->after('paypal_plan_id');
        });
    }

    public function down() {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'basic_amount', 'tax', 'paypal_plan_id', 'paypal_trial_plan_id']);
        });
    }

}
