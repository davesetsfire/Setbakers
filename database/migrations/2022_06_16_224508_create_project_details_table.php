<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('project_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('project_name', 190)->default('');
            $table->string('project_email', 190)->nullable();
            $table->text('description')->nullable();
            $table->string('company_name', 200)->default('');
            $table->string('house_number', 100)->default('');
            $table->string('street', 200)->default('');
            $table->string('postal_code', 20)->default('');
            $table->string('location', 200)->default('');
            $table->string('country', 100)->default('');
            $table->string('logo_image_path', 200)->default('');
            $table->string('paypal_subscription_id', 255)->nullable();
            $table->bigInteger('subscription_id')->default(0);
            $table->boolean('is_subscription_paused')->default(0);
            $table->dateTime('subscription_start_date')->nullable();
            $table->dateTime('subscription_end_date')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
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
        Schema::dropIfExists('project_details');
    }

}
