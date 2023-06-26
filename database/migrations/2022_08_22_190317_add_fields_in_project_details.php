<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInProjectDetails extends Migration {

    public function up() {
        Schema::table('project_details', function (Blueprint $table) {
            $table->string('ust_id', 50)->default('')->after('company_name');
            $table->boolean('is_company')->default(1)->after('ust_id');
        });
    }

    public function down() {
        Schema::table('project_details', function (Blueprint $table) {
            $table->dropColumn([
                'ust_id',
                'is_company'
            ]);
        });
    }

}
