<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
    $table->id();
    $table->uuid('matchId')->unique();
    $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
    $table->date('matchStartDate');
    $table->time('matchStartTime');
    $table->foreignId('teamAId')->constrained('teams');
    $table->foreignId('teamBId')->constrained('teams');
    $table->string('location');
    $table->string('status')->default('Pending');
    $table->integer('teamOneScore')->nullable();
    $table->integer('teamTwoScore')->nullable();
    $table->foreignId('winnerTeamId')->nullable()->constrained('teams');
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
        Schema::dropIfExists('matches');
    }
}
