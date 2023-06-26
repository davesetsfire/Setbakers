<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalPlansTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('paypal_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->string('plan_code');
            $table->string('name');
            $table->string('description');
            $table->decimal('amount', 8, 2)->default(0.00);
            $table->string('currency', 20)->default('');
            $table->integer('duration')->default(0);
            $table->enum('duration_in', ['DAY', 'WEEK', 'MONTH', 'YEAR']);
            $table->integer('trial_duration')->default(0);
            $table->enum('trial_duration_in', ['DAY', 'WEEK', 'MONTH', 'YEAR']);
            $table->boolean('is_active')->default(1);
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('paypal_plans');
    }

}
