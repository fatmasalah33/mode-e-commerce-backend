<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;



class RegisterController extends Controller
{
    public function register(Request $request)

    {
        $validator = $request->validate([
            'email' => ['required', 'email','unique:users'],
            'password' => ['required'],
            //'c_password' => 'required|same:password',
            'name' => ['required', 'string'],
            'phone' => ['unique:users','digits:11','starts_with:010,011,012','numeric']

        ]);
        // if ($validator->fails())
        // {
        //     return response()->json($validator->errors(), 422);
        // }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);
            return response()->json( "Successfully Register",200);





    }
}
