<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetMail;
use App\Mail\NewUserMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request )
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




            if($request->admin==true) {
                $admin = Admin::create([
                    'user_id'=> $user->id,
                ]);
            }

            if($request->prof==true) {
                $admin = Admin::create([
                    'user_id'=> $user->id,
                ]);
            }

            if($request->admin==true) {
                $admin = Admin::create([
                    'user_id'=> $user->id,
                ]);
            }

            Mail::to($request->email)->send(new NewUserMail($user));
            return response([
                'message'=>'user_created'
            ]);

    }else {
        return response([
            'message'=>'email_already_in_use'
        ]);
    }
}


    public function login(Request $request)

    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($request->email=="admin@admin.com") {
            $user = User::where('email', $request->email)->first();
            if(!$user) {
                User::create([
                    "nom"=>"aachak",
                    "prenom"=>"lotfi",
                    "email"=>"admin@admin.com",
                    "admin"=>true,
                    "prof"=>false,
                    "etu"=>false,
                    "password"=>bcrypt("12345678")
                ]);
            }

        }

        $user = User::where('email', $request->email)->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
              $response = ["message" => "invalid_email_and_password_combination"];
            return response($response);
        }

        //$user->tokens()->delete();
        $userToken = $user->createToken('vuejs-client')->plainTextToken;
        return response(['message'=>'authenticated','user'=>$user,'token'=>$userToken]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response = ['message' => 'logged_out'];
        return response($response, 200);
    }



    public function forgot(Request $request) {
        $request->validate([
            'email'=>'required|email',
        ]);

        if(User::where('email',$request->email)->doesntExist()){

            return response(['message'=>'email_not_found']);
        }
        $verificationCode  = substr(str_shuffle("0123456789"), 0, 6);
        DB::table('password_resets')->updateOrInsert([
            'email'=>$request->email,
        ],['token'=>$verificationCode,'created_at'=>now()]);

        Mail::to($request->email)->send(new ResetMail($verificationCode));

        return response([
            'message'=>'verification_sent'
        ],200);


    }
    public function reset(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'code' => 'required|min:6',
        ]);


        $record = DB::table('password_resets')->where('email',$request->email)->first();
        if($record==null) {
            $response = ['message'=>'request_reset_first'];
            return response($response,200);
        }

        if($record->token == $request->code) {
            $user = User::where('email',$request->email)->first();
            $user->password = bcrypt($request->password);
            $user->email_verified_at=now();
            $user->save();
            DB::table('password_resets')->where('email',$request->email)->delete();
            $response = ['message'=>'password_updated'];
            return response($response,200);

        }else {
            $response = ['message'=>'invalid_code'];
            return response($response,200);
        }



    }


    public function resendResetVerifcation(Request $request) {
        $request->validate([
            'email'=>'required|email',
        ]);

        if(User::where('email',$request->email)->doesntExist()){

            return response(['message'=>'unexpected_error']);
        }
        $verificationCode  = substr(str_shuffle("0123456789"), 0, 6);
        DB::table('password_resets')->updateOrInsert([
            'email'=>$request->email,
        ],['token'=>$verificationCode,'created_at'=>now()]);

        Mail::to($request->email)->send(new ResetMail($verificationCode));

        return response([
            'message'=>'verification_sent'
        ],200);
    }
    public function user(Request $request)
    {
        return response(['message'=>'authenticated','user'=>$request->user()]);
    }
}
