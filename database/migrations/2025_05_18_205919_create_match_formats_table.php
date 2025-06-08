<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('match_formats', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
    $table->string('matchType');
    $table->string('matchPointFormat');
    $table->string('bestOf');
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
        Schema::dropIfExists('match_formats');
    }
}
