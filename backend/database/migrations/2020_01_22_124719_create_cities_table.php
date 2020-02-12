<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_utf')->nullable();
            $table->decimal('lng', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->string('country_name')->nullable();
            $table->string('region_name')->nullable();
            $table->string('iso_2')->nullable();
            $table->string('iso_3')->nullable();
            $table->bigInteger('population')->nullable()->default(0);
            $table->boolean('capital')->nullable()->default(false);
            $table->boolean('enabled')->default(true);
            $table->bigInteger('outer_id')->nullable();
            $table->text('outer_source')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE cities ADD FULLTEXT INDEX fulltext_name_idx(`name`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
