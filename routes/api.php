<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEtu;
use App\Http\Middleware\IsProf;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/forgot', [AuthController::class, 'forgot']);
Route::post('/reset', [AuthController::class, 'reset']);
Route::post('/resendconfirmation', [AuthController::class, 'resendConfirmationVerifcation']);
Route::post('/resendreset', [AuthController::class, 'resendResetVerifcation']);


Route::post('/createuser',[AdminController::class,'createUser']);


Route::group(['middleware' => ['auth:sanctum'],], function ($route) {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);



    //les routes Admin

    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/infos',[AdminController::class,'dashboardInfos']);
        Route::get('/modules',[AdminController::class,'modules']);
        Route::post('/etudiants',[AdminController::class,'etudiants']);
        Route::post('/enseignants',[AdminController::class,'enseignants']);
        Route::post('/ajouterclasse',[AdminController::class,'ajouterClasse']);
        Route::post('/modifierclasse',[AdminController::class,'modifierClasse']);
        Route::post('/supprimerclasse',[AdminController::class,'supprimerClasse']);
        Route::post('/ajouteretu',[AdminController::class,'ajouterEtu']);
        Route::post('/modifieretu',[AdminController::class,'modifierEtu']);
        Route::post('/ajouterprof',[AdminController::class,'ajouterProf']);
        Route::post('/modifierprof',[AdminController::class,'modifierProf']);
        Route::post('/ajoutermod',[AdminController::class,'ajouterMod']);
        Route::post('/modifiermod',[AdminController::class,'modifierMod']);
        Route::post('/supprimermod',[AdminController::class,'supprimerMod']);
        Route::post('/ajouterseance',[AdminController::class,'ajouterSeance']);
        Route::post('/supprimerseance',[AdminController::class,'supprimerSeance']);

        Route::post('/deleteuser',[AdminController::class,'deleteUser']);
    });



    //les routes Enseignant
    Route::middleware([IsProf::class])->group(function () {

    });


    //les routes Etudiant
    Route::middleware([IsEtu::class])->group(function () {

    });

});
