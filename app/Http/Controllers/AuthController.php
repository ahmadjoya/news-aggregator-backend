<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request){

        $post_data = $request->validate([
                'name'=>'required|string',
                'email'=>'required|string|email|unique:users',
                'password'=>'required|min:8'
        ]);

            $user = User::create([
            'name' => $post_data['name'],
            'email' => $post_data['email'],
            'password' => Hash::make($post_data['password']),
            ]);

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            ]);
        }

        public function login(Request $request){
        if (!\Auth::attempt($request->only('email', 'password'))) {
               return response()->json([
                'message' => 'Login information is invalid.'
              ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
            ]);
        }

        public function updateUserInfo(Request $request){

            $validated = $request->validate([
                'email'=>'string|email|unique:users',
            ]);

            User::where('id', $request->user()->id)->update([
                'email' => $request->email
             ]);

            return response()->json([
                'email' => $request->email
            ]);
        }

        public function changePassword(Request $request){
            $user = User::findOrFail($request->user()->id);

            $post_data = $request->validate([
                'currentPassword'=> 'required|string',
                'newPassword'=> "required|string|min:8"
            ]);


            if(Hash::check($request->currentPassword, $user->password)){
                $user->fill([
                    'password' => Hash::make($request->newPassword)
                    ])->save();
                return response()->json([
                    'success' => true,
                ]);

           }else{

            return response()->json([
                'message' => 'Password mis matched'
              ], 401);

           }
        }
}
