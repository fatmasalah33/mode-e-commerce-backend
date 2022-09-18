<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Auth;


class UserController extends Controller
{


    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index(Request $request)
    {
        $users=User::where('role_id','=',"3")->get();
        $sellers=User::where('role_id','=',"2")->get();
        $admins=User::where('role_id','=',"1")->get();
        return  response()->json([
            'users' => $users,
            "admin"=>$admins,
            'sellers'=>$sellers

        ],200);


    }
    public function alluser(){
        return   User::all() ;  
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user=User::find($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }



    //ChangePassword
    public function ChangePassword(Request $request, $id)
    {

        $user=User::find($id);

        if (Hash::check($request->oldpassword, $user->password) && $request->newpassword===$request->confirmpassword) {
            $user->password=Hash::make($request->newpassword);
            $user->save();

            return response()->json("Succes Update Password", 200);
        }

       return response()->json("Please Insert Valid Data", 403);
    }



    // show login button view
    public function socialLogin(){
        return view("login");
    }

    public function redirectToGoogle(){
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleGoogleCallback(){
        $socialuser = Socialite::driver('facebook')->stateless()->user();
        // dd($socialuser->getAvatar());

        $user      =   User::where(['email' => $socialuser->getEmail()])->first();
        if(!$user){
            $user = User::firstOrCreate([
                'name'          => $socialuser->getName(),
                'email'         => $socialuser->getEmail(),
                'role_id'       =>3,
                'password'      =>encrypt('123456dummy')
            ]);
        }
       // $token = $user->createToken('token-name')->plainTextToken;

        return response()->json($user, 200);
        // Auth::login($user);
       // return  ('welcom in home page');
    }

}
