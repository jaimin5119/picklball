<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
    $table->id();
    $table->uuid('tournamentId')->unique();
    $table->string('tournamentName');
    $table->string('tournamentLogo')->nullable();
    $table->string('tournamentBanner')->nullable();
    $table->dateTime('startDate');
    $table->dateTime('endDate');
    $table->text('description')->nullable();
    $table->string('organizerName');
    $table->string('organizerPhone');
    $table->string('location');
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
        Schema::dropIfExists('tournaments');
    }
}
