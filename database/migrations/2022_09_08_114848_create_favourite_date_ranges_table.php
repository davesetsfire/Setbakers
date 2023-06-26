<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Favourite;
use App\Models\FavouriteDateRange;

class CreateFavouriteDateRangesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('favourite_date_ranges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('favourite_id');
            $table->foreign('favourite_id')->references('id')->on('favourites');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $favourites = Favourite::where('user_id', '>', 0)->get();
        foreach ($favourites as $favourite) {
            FavouriteDateRange::create([
                'favourite_id' => $favourite->id,
                'start_date' => $favourite->start_date,
                'end_date' => $favourite->end_date,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('favourite_date_ranges');
    }

}
