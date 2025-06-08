<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_configs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
    $table->string('tournamentType');
    $table->string('tournamentFormat');
    $table->string('seedingType');
    $table->integer('numberOfTeams');
    $table->string('knockoutStartRound')->nullable();
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
        Schema::dropIfExists('tournament_configs');
    }
}
