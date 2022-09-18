<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {




        // $this->middleware('auth:api', ['except' => ['login','register','verifyAccount']]);

        // $this->middleware('auth:api', ['except' => ['login','register','redirectToProvider','handleProviderCallback']]);

        // $this->middleware('auth:api', ['except' => ['login','register','verifyAccount']]);

        // $this->middleware('auth:api', ['except' => ['login','register','redirectToProvider','handleProviderCallback','verifyAccount']]);



        $this->middleware('auth:api', ['except' => ['login','register','verifyAccount','redirectToProvider','handleProviderCallback','forgetpassword','updatepassword']]);

        // $this->middleware('auth:api', ['except' => ['login','register','verifyAccount']]);




    }

    public function login()
    {
        $credentials = request(['email', 'password']);


        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // return $this->respondWithToken($token);
        return  response()->json([
            'status' => 'success',
            "token"=>$token,
            'user'=>Auth::user(),
           'user_type'=>Auth::user()->Roles->name
        ],200);
    }

// public function login(Request $request)
// {
//     $request->validate([
//         'email' => 'required|string|email',
//         'password' => 'required|string',
//     ]);
//     $credentials = $request->only('email', 'password');

//     $token = Auth::attempt($credentials);
//     if (!$token) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Unauthorized',
//         ], 401);
//     }

//     $user = Auth::user();
//     return response()->json([
//             'status' => 'success',
//             'user' => $user,
//             'authorisation' => [
//                 'token' => $this->createNewToken($token),
//                 'type' => 'bearer',
//             ]
//         ]);

// }

public function register(Request $request){
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'phone' => ['unique:users','digits:11','starts_with:010,011,012','numeric']
    ]);

    $user= User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
    ]);
       //mail verification
       $verification_code =random_int(100000, 999999);//Generate verification code
       DB::table('user_verifications')->insert(['user_id'=>$user->id,'verification_code'=>$verification_code]);
       $subject = "Please verify your email address.";
       $email=$request->email;
       $name=$request->name;
       Mail::send('maile', ['name' =>$name , 'verification_code' => $verification_code],
           function($mail) use ( $subject,$email,$name){
               $mail->from('gradproj763@gmail.com', "From Moda");
               $mail->to($email, $name);
               $mail->subject($subject);
           });
    $token = Auth::login($user);
    return response()->json([
        'status' => 'success',
        'message' => 'please check your email',
        'user' => $user,
        'authorisation' => [
            'token' => $token,
            'type' => 'bearer',
        ]
    ]);


}

public function logout()
{
    Auth::logout();
    return response()->json([
        'status' => 'success',
        'message' => 'Successfully logged out',
    ]);
}

public function refresh()
{
    return response()->json([
        'status' => 'success',
        'user' => Auth::user(),
        'authorisation' => [
            'token' => Auth::refresh(),
            'type' => 'bearer',
        ]
    ]);
}

protected function respondWithToken($token)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 360
    ]);
}


public function verifyAccount(Request $request ){
    $user_id= DB::table('users')->where('email', $request->email)->value('id');
    $check_verify = DB::table('user_verifications')->where('user_id', $user_id)->value('verification_code');
    if($check_verify==$request->verification_code && $check_verify!=null && $user_id!=null) {
        DB::table('users')
        ->where('id', $user_id)  // find your user by their email
        ->update(array('email_verified_at' => now()));  // update the record in the DB.
        return response()->json([
            'message'=>'account has been verified successfully',
            'check_verify'=>$check_verify,
            'user_id'=>$user_id
        ]);
    }
    return response()->json([
        'message'=>'your verfifcation code was wrong ,please try again'
    ]);
}
public function forgetpassword(Request $request ){
    $user_id= DB::table('users')->where('email', $request->email)->value('id');
    if($user_id!=null){
      $subject = "forget your password";
       $email=$request->email;
       Mail::send('password', ['user_id' =>$user_id ],
           function($mail) use ( $subject,$email,$user_id){
               $mail->from('gradproj763@gmail.com', "From jumia");
               $mail->to($email);
               $mail->subject($subject);
           });
           return  response()->json([
            'status' => 'success',
        ],200);

    }
    return response()->json([

           'message'=>'your email was wrong ,please try again'
        ], 401);

}
public function updatepassword(Request $request, $id){
    $user= User::find($id);
    if( !is_null($request->password)){
        $user->password=Hash::make($request->password);
        $user->save();
        return response()->json([
            'status' => 'success',
        ],200);
    }else{
        return response()->json([

            'message'=>'please insert a valid password'
         ], 401);
    }



}




//Social Login (FaceBook and GoogleGmail)
public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'role_id'           =>3,

            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        //$token = $userCreated->createToken('token-name')->plainTextToken;
        $token = Auth::login($userCreated);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $userCreated,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return response()->json(['error' => 'Please login using facebook  or google'], 422);
        }
    }


}
