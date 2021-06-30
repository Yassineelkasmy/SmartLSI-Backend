<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            //   Pour representer un jour dans la semaine , un nombre de 1 jusqu`au 7 ,
            //  (meme si il est tres rare d avoir une seance a une dimanche (7))

            $table->tinyInteger('jour')->nullable();

            // Format XX:XX
            $table->string('h_debut',5);
            $table->string('h_fin',5);
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seances');
    }
}
