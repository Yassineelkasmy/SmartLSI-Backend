<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClasseEnseignant extends Migration
{
    /**p
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classe_enseignant', function (Blueprint $table) {
            $table->foreignId('classe_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classe_enseignant');
    }
}
