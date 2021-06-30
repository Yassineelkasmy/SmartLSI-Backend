<?php

namespace App\Http\Controllers;

use App\Mail\NewUserMail;
use App\Models\Admin;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Saison;
use App\Models\Seance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use stdClass;

class AdminController extends Controller
{
    public function createUser(Request $request )
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'admin' => 'required|boolean',
            'prof' => 'required|boolean',
            'etu' => 'required|boolean',
            'password' => 'required|min:8',
        ]);

        //Valider qu'il faut specifier le type d`utilisateur
        // et un utilisateur ne peut pas etre admin , prof , etu
        // en meme temps
        $falses = [false , false ,false];
        $trues = [true , true ,true];
        $choix = [$request->admin,$request->prof,$request->etu];
        if($choix==$falses || $choix == $trues) {
            return response([
                'message'=>'error_unauthorized'
            ]);
        }
        $user =User::where('email',$request->email)->first();
        if($user==null){

            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'admin' => $request->admin,
                'prof' => $request->prof,
                'etu' => $request->etu,
                'password' => bcrypt($request->password),
            ]);



            $infos = new stdClass();

            //Pour infos sur l`admin qui est entrain
            //de creer cet utilisateur
            $infos->admin = $request->user;
            $infos->user = $user;
            $infos->password = $request->password;

            if($request->admin==true) {
                Admin::create([
                    'user_id'=> $user->id,
                ]);

                $infos->admin = true;

            }

            if($request->prof==true) {
            Enseignant::create([
                    'user_id'=> $user->id,
                    'cnp'=>$request->cnp,
                ]);

                $infos->prof = true;
            }

            if($request->etu==true) {
                Etudiant::create([
                    'user_id'=> $user->id,
                    'cne'=>$request->cne,
                    'classe_id'=>$request->classe_id,
                ]);



                $infos->etu = true;
                $infos->classe = Classe::find($request->classe_id);

            }
            Mail::to($request->email)->send(new NewUserMail($infos));
            return response([
                'message'=>'user_created'
            ]);

    }else {
        return response([
            'message'=>'email_already_in_use'
        ]);
    }
}
    public function deleteUser(Request $request) {
        $request->validate([
            "id"=>"required"
        ]);

        $user = User::find($request->id);
        if($user) {
            $user->delete();
        }


    }
    public function dashboardInfos() {
        $totalEnseignants = Enseignant::all()->count();
        $totalEtudiants = Etudiant::all()->count();
        $totalModules = Module::all()->count();
        $totalClasses = Classe::all()->count();


        return response([
            'enseignants'=>$totalEnseignants,
            'etudiants'=>$totalEtudiants,
            'modules'=>$totalModules,
            'classes'=>$totalClasses,
        ]);
    }



    public function etudiants() {

        $classes = Classe::all();

        foreach ($classes as $clss) {
            $modules = $clss->modules;
            foreach ($modules as $module) {
                $module->enseignant->user;
            }
            $etus = $clss->etudiants;
            $seances = $clss->seances;
            foreach($etus as $etu) {
                $etu->user;
            }

            foreach($seances as $seance) {
                $seance->module;
            }


        }


        return response([
            "classes"=>$classes,


            ]
        );



    }

    public function enseignants() {
        $ens = Enseignant::all();
        foreach ($ens as $en ) {
            $en->modules;
            $en->user;

        }
        return $ens;
    }





    public function ajouterEtu(Request $request) {

        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'cne' => 'required',
            'classe_id' => 'required',
            'password' => 'required|min:8',
        ]);



        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'admin' => false,
            'prof' => false,
            'etu' => true,
            'password' => bcrypt($request->password),
        ]);

        $etu = Etudiant::create([
            'user_id'=> $user->id,
            'cne'=>$request->cne,
            'classe_id'=>$request->classe_id,
        ]);



        return $this->etudiants();
    }


    public function modifierEtu(Request $request) {

        $request->validate([
            'id' => 'required',
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'cne' => 'required',
            'classe_id' => 'required',
        ]);



        $user = User::find($request->id);
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->save();
        $etu = Etudiant::firstWhere("user_id",$user->id,);
        $etu->cne = $request->cne;
        $etu->save();




        return $this->etudiants();
    }


    public function ajouterProf(Request $request) {

        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'cnp' => 'required',
            'password' => 'required|min:8',
        ]);



        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'admin' => false,
            'prof' => true,
            'etu' => false,
            'password' => bcrypt($request->password),
        ]);

        Enseignant::create([
            'user_id'=> $user->id,
            'cnp'=>$request->cnp,
        ]);



        return $this->enseignants();
    }

    public function modifierProf(Request $request) {
        $request->validate([
            'id'=>'required',
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'cnp' => 'required',
        ]);


        $user = User::find($request->id);
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->save();
        $prof = Enseignant::firstWhere("user_id",$user->id,);
        $prof->cnp = $request->cnp;
        $prof->save();


        return $this->enseignants();

    }



    public function ajouterAdmin(Request $request) {

        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);



        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'admin' => true,
            'prof' => false,
            'etu' => false,
            'password' => bcrypt($request->password),
        ]);

        Admin::create([
            'user_id'=> $user->id,
        ]);



        return response([
            'message'=>'user_created'
        ]);
    }

    public function ajouterClasse(Request $request) {
        $request->validate(
            [
                "classe"=>"required",
            ]
        );

        $classe = Classe::create([
            "classe"=>$request->classe
        ]);

        $classe->etudiants;


        return $classe;

    }


    public function modifierClasse(Request $request) {
        $request->validate(
            [
                "classe"=>"required",
                "classe_id"=>"required",
            ]
        );

        $classe = Classe::find($request->classe_id);
        $classe->classe = $request->classe;
        $classe->save();

       return $this->etudiants();

    }

    public function supprimerClasse(Request $request) {
        $request->validate(
            [
                "classe_id"=>"required",
            ]
        );


       $classe = Classe::find($request->classe_id);
       foreach ($classe->etudiants as $etu) {
            $etu->user->delete();
       }
       $classe->delete();
       return $this->etudiants();

    }
    public function ajouterMod(Request $request) {
        $request->validate(
            [
                "titre"=>"required",
                "classe_id"=>"required",
                "enseignant_id"=>"required",
            ]
        );

        Module::create([
            "titre"=>$request->titre,
            "classe_id"=>$request->classe_id,
            "enseignant_id"=>$request->enseignant_id,
        ]);


        return $this->etudiants();
    }

    public function modifierMod(Request $request) {
        $request->validate(
            [
                "titre"=>"required",
                "module_id"=>"required",
            ]
        );

        $mod = Module::find($request->module_id);
        if($mod) {
            $mod->titre = $request->titre;
            $mod->save();
        }


        return $this->etudiants();
    }


    public function supprimerMod(Request $request) {
        $request->validate(
            [
                "id"=>"required",

            ]
        );

        $mod = Module::find($request->id);
        if($mod) {
            $mod->delete();
        }


        return $this->etudiants();
    }

    public function modules() {
        return Module::all();
    }

    public function ajouterSeance(Request $request) {
        $request->validate([
            "module_id"=>"required",
            "jour"=>"required",
            "h_debut"=>"required",
            "h_fin"=>"required",
        ]);

        $classe = Module::find($request->module_id)->classe;

        Seance::create([
            "module_id"=>$request->module_id,
            "jour"=>$request->jour,
            "h_debut"=>$request->h_debut,
            "h_fin"=>$request->h_fin,
            "classe_id"=>$classe->id,
        ]);


        return $this->etudiants();
    }

    public function supprimerSeance(Request $request) {
        $request->validate([
            "id"=>"required"
        ]);

        $seance = Seance::find($request->id);
        if($seance) {
            $seance->delete();
        }

        return $this->etudiants();
    }

}
