<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function module() {
        return $this->belongsTo(Module::class);
    }



    protected $fillable = [
        'jour',
        'h_debut',
        'h_fin',
        'module_id',
        'classe_id',

    ];
}
