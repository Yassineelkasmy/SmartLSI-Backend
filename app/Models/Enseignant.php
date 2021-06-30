<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cnp'];


    public $timestamps = false;

    public function modules() {
        return $this->hasMany(Module::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
