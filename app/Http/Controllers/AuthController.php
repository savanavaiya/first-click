<?php

namespace App\Http\Controllers;

use App\Models\Importdata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {

            // $validate = $request->validate([
            //     'email' => 'required|email|unique:users,email',
            //     // 'password' => 'required|min:6|same:confirm_password',
            //     'password' => 'required|min:6',
            //     // 'confirm_password' => 'required|min:6',
            //     'user_type' => 'required',
            // ]);

            $validate = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_image' => '',
            ]);

            $token =  $data->createToken('MyApp')->plainTextToken;

            return response()->json(['success' => 'true', 'message' => 'User Registered Successfully', 'token' => $token , 'data' => $data], 200);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function login(Request $request)
    {
        try {

            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            {
                $user = Auth::user();

                $token =  $user->createToken('MyApp')->plainTextToken;

                return response()->json(['success' => 'true', 'message' => 'User Logged In Successfully', 'token' => $token , 'data' => $user], 200);
            }else
            {
                return response()->json(['success' => 'false', 'message' => 'Unauthorised',], 401);
            }

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function updateprofile(Request $request)
    {

        $validate = $request->validate([
            'name' => 'required',
        ]);


        $id = auth()->user()->id;

        $data = User::find($id);

        $data->name = $request->name;

        if($request->hasFile('profile_image')){

            $img = 'IMG'.'.'.rand('1111','9999').time().'.'.'png';

            $request->profile_image->move(public_path('images/'),$img);

            $data->profile_image = $img;
        }

        $data->save();

        return response()->json(['success' => 'true', 'message' => 'User Profile Updated Successfully','data' => $data], 200);

    }

    public function logout()
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response()->json(['success' => 'true', 'message' => 'User Logged Out Successfully'], 200);
    }

    public function userdelete()
    {
        $tok = Auth::user();
        // dd($tok->id);
        $user = User::where('id',$tok->id)->first();

        if($user->profile_image != null){
            if (file_exists(public_path('images/'.$user->profile_image))) {

                @unlink(public_path('images/'.$user->profile_image));

            }
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['success' => 'true', 'message' => 'User Deleted Successfully'], 200);
    }
}
