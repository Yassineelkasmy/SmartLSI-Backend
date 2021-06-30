<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    public $timestamps = false;

    protected $primaryKey = 'id';


    public function etudiants() {
        return $this->hasMany(Etudiant::class);
    }
    public function modules() {
        return $this->hasMany(Module::class);
    }

    public function seances() {
        return $this->hasMany(Seance::class);
    }



    // public $timestamps = false;

    protected $fillable = [
        "classe"
    ];




}
