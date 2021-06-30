<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use Illuminate\Http\Request;

class ProfController extends Controller
{
    public function modules(Request $request) {
        $en = Enseignant::find($request->user()->id);
    }
}
