<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            // la note est represente par un float de 00.00 -> 20.00
            // 2 ($total = 8 , par defaut) -> le nombre des chiffres de la partie entiere
            // et Laravel par defaut donne 2 ($places=2 , par defaut) comme nombre des chiffres
            //  dans la partie decimal
            // Aussi true: ($unsigned = false , par defaut) et les notes sont toujours positifs
            $table->float('note',$total=2 , $unsigned=true);
            $table->foreignId('module_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('etudiant')->constrained()->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('notes');
    }
}
