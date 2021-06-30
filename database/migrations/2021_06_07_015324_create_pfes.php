<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePfes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pfes', function (Blueprint $table) {
            //  Cette migration a ete cree manuellement (php artisan make:migration CreatePfes)
            //  Car le model nomme Pfe n`a pas bien transforme au nomme du table
            //  par Laravel (Pves -> pas de sens)
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('pfes');
    }
}
