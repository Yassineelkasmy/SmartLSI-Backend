<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [ "titre","classe_id","enseignant_id"];

    public function classe() {
        return $this->belongsTo(Classe::class);
    }

    public function enseignant() {
        return $this->belongsTo(Enseignant::class);

    }


}
