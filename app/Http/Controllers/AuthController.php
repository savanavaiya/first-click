<?php

namespace App\Http\Controllers;

use App\Models\Importdata;
use App\Models\Normaluser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {

            $validate = $request->validate([
                'firstclick_name' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'city' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:5|max:10',
                // 'phone' => 'required',
            ]);

            $data = User::create([
                'firstclick_name' => $request->firstclick_name,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'city' => $request->city,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'status' => 0,
                'profile_image' => '',
                'user_type' => 'admin',
            ]);

            // $token =  $data->createToken('MyApp')->plainTextToken;

            // return response()->json(['success' => 'true', 'message' => 'User Registered Successfully', 'token' => $token , 'data' => $data], 200);
            // return response()->json(['success' => 'true', 'message' => 'User Registered Successfully', 'data' => $data], 200);
            return response()->json(['success' => 'true', 'message' => 'Thanks for signing up! Your account is pending approval. Try logging in after 24 hours.', 'data' => $data], 200);
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

            $requser = User::where('email', $request->email)->first();

            if ($requser != null) {
                if (Hash::check($request->password, $requser->password)) {
                    if ($requser->status == '1') {
                        Auth::login($requser);

                        $user = Auth::user();

                        $token =  $user->createToken('MyApp')->plainTextToken;

                        return response()->json(['success' => 'true', 'message' => 'User Logged In Successfully', 'token' => $token, 'data' => $user], 200);
                    } elseif ($requser->status == '0') {
                        // return response()->json(['success' => 'true', 'message' => 'Your request is in pending', 'data' => $requser], 200);
                        return response()->json(['success' => 'true', 'message' => 'Your account is pending approval. For more information, please email us at', 'data' => $requser], 200);
                    } elseif ($requser->status == '2') {

                        return response()->json(['success' => 'true', 'message' => 'Your request is rejected', 'reason' => $requser->reason_of_reject, 'data' => $requser], 200);
                    }
                } else {
                    return response()->json(['success' => 'false', 'message' => 'Invalid email or password'], 401);
                }
            } else {
                return response()->json(['success' => 'false', 'message' => 'Invalid email'], 401);
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function updateprofile(Request $request)
    {

        $validate = $request->validate([
            'firstclick_name' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'city' => 'required',
            // 'phone' => 'required',
        ]);


        $id = auth()->user()->id;

        $data = User::find($id);

        $data->firstclick_name = $request->firstclick_name;
        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->city = $request->city;
        $data->phone = $request->phone;

        if ($request->hasFile('profile_image')) {

            $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

            $request->profile_image->move(public_path('images/'), $img);

            $data->profile_image = $img;
        }

        $data->save();

        return response()->json(['success' => 'true', 'message' => 'User Profile Updated Successfully', 'data' => $data], 200);
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
        $user = User::where('id', $tok->id)->first();

        $stationdatas = Importdata::where('user_id',$user->id)->get();

        foreach($stationdatas as $stationdata)
        {
            $stationdata->user_id = '0';
            $stationdata->modify_name = 'Firstclick';
            $stationdata->save();
        }

        if ($user->profile_image != null) {
            if (file_exists(public_path('images/' . $user->profile_image))) {

                @unlink(public_path('images/' . $user->profile_image));
            }
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['success' => 'true', 'message' => 'Your account has been deleted successfully'], 200);
    }

    public function rejectedupd(Request $request)
    {
        // dd('okay');
        $validate = $request->validate([
            'id' => 'required',
            'firstclick_name' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'city' => 'required',
            'email' => 'required|email',
            // 'phone' => 'required',
        ]);

        $id = $request->id;
        $data = User::find($id);

        if($data != null){
            $data->firstclick_name = $request->firstclick_name;
            $data->firstname = $request->firstname;
            $data->lastname = $request->lastname;
            $data->city = $request->city;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->status = 0;
            $data->reason_of_reject = null;
            $data->save();

            // return response()->json(['success' => 'true', 'message' => 'Your Profile Updated Successfully and the same has been awaiting for approval at administration.', 'data' => $data], 200);
            return response()->json(['success' => 'true', 'message' => 'Thanks for updating your information! Your account is pending approval. Try logging in after 24 hours.', 'data' => $data], 200);
        }else{
            return response()->json(['success' => 'false', 'message' => 'Something went wrong'], 401);
        }
    }
}
