<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundusDowngradeRequestsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fundus_downgrade_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fundus_id')->index();
            $table->foreign('fundus_id')->references('id')->on('fundus_details');
            $table->string('current_package')->default('');
            $table->string('new_package')->default('');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
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
        Schema::dropIfExists('fundus_downgrade_requests');
    }

}
