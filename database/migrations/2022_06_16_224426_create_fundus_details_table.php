<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateFundusDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fundus_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('fundus_name', 190)->default('')->unique();
            $table->string('fundus_email', 190)->default('');
            $table->string('fundus_phone', 25)->nullable();
            $table->string('owner_first_name', 100)->default('');
            $table->string('owner_last_name', 100)->default('');
            $table->text('description')->nullable();
            $table->string('company_name', 200)->default('');
            $table->string('website', 100)->nullable();
            $table->string('house_number', 100)->default('');
            $table->string('street', 200)->default('');
            $table->string('postal_code', 20)->default('');
            $table->string('location', 200)->default('');
            $table->geometry('geo_location')->nullable();
            $table->string('country', 100)->default('');
            $table->string('logo_image_path', 200)->default('');
            $table->string('paypal_subscription_id', 255)->nullable();
            $table->boolean('is_paused')->default(0);
            $table->dateTime('paused_at')->nullable();
            $table->date('paused_till_date')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        //DB::statement('ALTER TABLE cm_fundus_details ADD SPATIAL INDEX(geo_location)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('fundus_details');
    }

}
