<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'cne','classe_id'];
    public $timestamps = false;


    public function classes() {
        return $this->belongsToMany(Classe::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    //Pour obtenir la classe courante d`etudiant de
    // la saison courante on est oublige de definir
    // cette fonction (Accessor)
    public function getClasseAttribute(){
        return $this->classes->last();
    }
}
