<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePMResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('records');
            $table->text('remark')->nullable();
            $table->boolean('is_approved')->nullable();
            $table->boolean('media_checked')->nullable();
            $table->string('station_code')->nullable();
            $table->uuid('user_id')->nullable();
            $table->boolean('is_latest');
            $table->integer('constituency_id')->nullable();
            $table->bigInteger('others')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
