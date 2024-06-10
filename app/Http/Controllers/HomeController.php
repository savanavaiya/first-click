<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Brandlogo;
use App\Models\Forapprovalstation;
use App\Models\Importdata;
use App\Models\Pricechangereq;
use App\Models\Storeimage;
use App\Models\Storeimgforapp;
use App\Models\User;
use App\Models\Appversion;
use App\Models\Devicetoken;
use App\Models\Notupdstation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\Normal;
use Spatie\FlareClient\View;

use Google_Client;
use Google_Service_Sheets;
use Revolution\Google\Sheets\Facades\Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_Request;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    public function index()
    {
        $userdata = auth()->user();
        $totalcontributor = User::where('user_type', 'admin')->count();
        $totalcontributoractive = User::where('user_type', 'admin')->where('status', '1')->count();
        $totalcontributorinactive = User::where('user_type', 'admin')->where('status', '!=', '1')->count();
        $totalstation = Importdata::count();
        $totalapprovalstation = Forapprovalstation::count();
        $totalad = Ads::first();
        $totalbrand = Brandlogo::count();
        $appversion = Appversion::first();

        // if (isset($totalad->topadimage) != null && isset($totalad->bottomadimage) != null) {
        //     $countad = 2;
        // } elseif (isset($totalad->topadimage) != null || isset($totalad->bottomadimage) != null) {
        //     $countad = 1;
        // } else {
        //     $countad = 0;
        // }
        $countad = 0;
        if (isset($totalad->topadimage) != null) {
            $countad = $countad + 1;
        }
        if (isset($totalad->bottomadimage) != null) {
            $countad = $countad + 1;
        }
        if (isset($totalad->storeadimage) != null) {
            $countad = $countad + 1;
        }

        $totalad = $countad;

        $totalpriceapp = Pricechangereq::count();
        $storeimgapp = Storeimgforapp::count();
        return view('dashboard', compact('userdata', 'totalcontributor', 'totalcontributoractive', 'totalstation', 'totalcontributorinactive', 'totalad', 'totalbrand', 'totalpriceapp', 'storeimgapp', 'totalapprovalstation','appversion'));
    }

    public function form_submit(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data = User::where('email', $request->email)->first();

        if ($data != null) {
            if (Hash::check($request->password, $data->password)) {
                if ($data->user_type == 'super_admin' || $data->user_type == 'semi_super_admin') {
                    Auth::login($data);
                    return redirect()->route('index');
                } else {
                    return back()->with('ERROR', 'Email or Password is incorrect!');
                }
            } else {
                return back()->with('ERROR', 'Email or Password is incorrect!');
            }
        } else {
            return back()->with('ERROR', 'Email or Password is incorrect!');
        }
    }

    public function login()
    {
        return View('loginpage');
    }

    public function logout()
    {
        if (Auth::logout()) {
            return redirect()->route('login');
        } else {
            return back()->with('ERROR', 'Sorry, something went wrong. Please try again.');
        }
    }


    public function contributor(Request $request, $filter)
    {

        // return view('contributor',compact('datas'));

        if ($request->ajax()) {
            // dd($filter);
            $search = $request->get('search');

            if ($search != '') {
                // $datas = User::where('firstname', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%')->orderBy('created_at', 'desc');
                $datas = User::orWhere(function ($query) use ($search) {
                    $query->where('firstname', 'like', '%' . $search . '%')
                        ->where('email', 'like', '%' . $search . '%')
                        ->where('user_type', 'admin');
                })->orderBy('created_at', 'desc');
            } else {
                $datas = User::where('user_type', 'admin')->orderBy('created_at', 'desc');
                // $datas = User::orderBy('created_at', 'desc');
            }


            if ($filter == 'totaluser') {
                $datas = $datas->paginate(10);
            } elseif ($filter == 'activeuser') {
                $datas = $datas->where('status', '1')->paginate(10);
            } elseif ($filter == 'inactiveuser') {
                $datas = $datas->where('status', '!=', '1')->paginate(10);
            }
            // $datas = $datas->paginate(10);
            // dd($datas);


            $total_row = $datas->count();
            $data = view('datacontributor', compact('datas', 'total_row'))->render();
            $response['data'] = $data;
            return $response;
        }
        $userdata = auth()->user();
        return view('contributor', compact('userdata', 'filter'));
    }

    public function approveuser($id)
    {
        // dd($id);
        $data = User::find($id);
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('SUCCESS', 'New contributor has been approved.');
    }

    public function createnewuser(Request $request)
    {
        $validate = $request->validate([
            'firstclick_name' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'city' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
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

        return redirect()->back()->with('SUCCESS', 'New contributor has been created.');
    }

    public function edituser(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'id' => 'required',
            'firstclick_name' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'city' => 'required',
            'email' => 'required',
            // 'phone' => 'required',
            'points' => 'required',
        ]);

        $id = $request->id;

        $data = User::find($id);
        $data->firstclick_name = $request->firstclick_name;
        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->city = $request->city;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->points = $request->points;
        $data->save();

        return redirect()->back()->with('SUCCESS', 'Contributor information has been modified.');
    }

    public function removeuser(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);

        if ($user->profile_image != null) {
            if (file_exists(public_path('images/' . $user->profile_image))) {

                @unlink(public_path('images/' . $user->profile_image));
            }
        }

        $user->tokens()->delete();
        $user->delete();

        return redirect()->back()->with('SUCCESS', 'Contributor has been removed.');
    }

    public function rejectuser(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $data = User::find($id);
        $data->status = 2;
        $data->reason_of_reject = $request->reason;
        $data->save();

        return redirect()->back()->with('SUCCESS', 'Contributor has been rejected.');
    }

    public function adsmanagement()
    {
        $datas = Ads::where('id', '1')->first();
        $userdata = auth()->user();
        return view('adsmanagement', compact('datas', 'userdata'));
    }

    public function topadimage(Request $request)
    {
        $validate = $request->validate([
            'topadimage' => 'required'
        ], [
            'topadimage.required' => 'Please select top ad image',
        ]);

        $ads = Ads::find(1);


        if ($ads == null) {
            if ($request->hasFile('topadimage')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->topadimage->move(public_path('images/ads/'), $img);
            }

            $data = Ads::create([
                'topadimage' => $img,
            ]);
        } else {

            if ($ads->topadimage != null) {
                if (file_exists(public_path('images/ads/' . $ads->topadimage))) {

                    @unlink(public_path('images/ads/' . $ads->topadimage));
                }
            }

            if ($request->hasFile('topadimage')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->topadimage->move(public_path('images/ads/'), $img);
            }

            $ads->topadimage = $img;
            $ads->save();
        }

        return redirect()->route('adsmanagement')->with('SUCCESS', 'Top header ad has been added.');
    }

    public function removetopadimage()
    {
        $data = Ads::find(1);

        if ($data->topadimage != null) {
            if (file_exists(public_path('images/ads/' . $data->topadimage))) {

                @unlink(public_path('images/ads/' . $data->topadimage));
            }
        }

        $data->topadimage = null;
        $data->save();

        return redirect()->route('adsmanagement')->with('SUCCESS', 'Top header ad has been removed. ');
    }

    public function bottomadimage(Request $request)
    {
        $validate = $request->validate([
            'bottomadimage' => 'required'
        ], [
            'bottomadimage.required' => 'Please select bottom ad image',
        ]);

        $ads = Ads::find(1);

        if ($ads == null) {
            if ($request->hasFile('bottomadimage')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->bottomadimage->move(public_path('images/ads/'), $img);
            }

            $data = Ads::create([
                'bottomadimage' => $img,
            ]);
        } else {

            if ($ads->bottomadimage != null) {
                if (file_exists(public_path('images/ads/' . $ads->bottomadimage))) {

                    @unlink(public_path('images/ads/' . $ads->bottomadimage));
                }
            }

            if ($request->hasFile('bottomadimage')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->bottomadimage->move(public_path('images/ads/'), $img);
            }

            $ads->bottomadimage = $img;
            $ads->save();
        }

        return redirect()->route('adsmanagement')->with('SUCCESS', 'Bottom header ad has been added.');
    }

    public function removebottomadimage()
    {
        $data = Ads::find(1);

        if ($data->bottomadimage != null) {
            if (file_exists(public_path('images/ads/' . $data->bottomadimage))) {

                @unlink(public_path('images/ads/' . $data->bottomadimage));
            }
        }

        $data->bottomadimage = null;
        $data->save();

        return redirect()->route('adsmanagement')->with('SUCCESS', 'Bottom header ad has been removed.');
    }

    public function storeadimage(Request $request)
    {
        $validate = $request->validate([
            'storeadimage' => 'required'
        ], [
            'storeadimage.required' => 'Please select store ad image',
        ]);

        $ads = Ads::find(1);

        if ($ads == null) {
            if ($request->hasFile('storeadimage')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->storeadimage->move(public_path('images/ads/'), $img);
            }

            $data = Ads::create([
                'storeadimage' => $img,
            ]);
        } else {

            if ($ads->storeadimage != null) {
                if (file_exists(public_path('images/ads/' . $ads->storeadimage))) {

                    @unlink(public_path('images/ads/' . $ads->storeadimage));
                }
            }

            if ($request->hasFile('storeadimage')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->storeadimage->move(public_path('images/ads/'), $img);
            }

            $ads->storeadimage = $img;
            $ads->save();
        }

        return redirect()->route('adsmanagement')->with('SUCCESS', 'Store ad has been added.');
    }

    public function removestoreadimage()
    {
        $data = Ads::find(1);

        if ($data->storeadimage != null) {
            if (file_exists(public_path('images/ads/' . $data->storeadimage))) {

                @unlink(public_path('images/ads/' . $data->storeadimage));
            }
        }

        $data->storeadimage = null;
        $data->save();

        return redirect()->route('adsmanagement')->with('SUCCESS', 'Store ad has been removed.');
    }

    public function totalstation(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('search');
            // dd($search);

            if ($search != '') {
                // $storedatas = Importdata::orWhere(function ($query) use ($search) {
                //     $query->where('store_name', 'like', '%' . $search . '%')
                //         ->where('brand', 'like', '%' . $search . '%');
                // })->get();
                $storedatas = Importdata::where('store_name', 'like', '%' . $search . '%')->orWhere('brand', 'like', '%' . $search . '%')->orWhere('city', 'like', '%' . $search . '%')->orWhere('landmarks', 'like', '%' . $search . '%')->get();
            } else {
                $storedatas = Importdata::all();
                // dd($storedatas);
            }

            $data = view('datastationsidemenu', compact('storedatas'))->render();
            $response['data'] = $data;
            return $response;
        }
        $userdata = auth()->user();

        // $storedatas = Importdata::all();
        $storedatasforapps = Forapprovalstation::all();
        $brand_datas = Brandlogo::all();
        // dd($storedatas);
        // if ($storedatas->isEmpty()) {
        //     return view('totalstationnull', compact('userdata'));
        // }

        $allcount = Importdata::count();
        $approvecount = Forapprovalstation::count();

        return view('totalstation', compact('userdata', 'storedatasforapps', 'allcount', 'approvecount', 'brand_datas'));
    }

    public function storepageright(Request $request)
    {
        // dd($request->all());
        $data = auth()->user();

        $storedatas = Importdata::all();
        $id = $request->id;
        $rightdata = Importdata::find($id);
        // dd($rightdata);

        // //for status check
        // $lastTuesday = Carbon::now()->previous(Carbon::TUESDAY);

        // $st = Notupdstation::where('station_id',$rightdata->id)->first();

        // if($st != null){
        //     $rightdata->status = 'Not Updated';
        // }else{
        //     if ($rightdata->updated_at >= $lastTuesday) {
        //         $rightdata->status = 'Updated';
        //     } else {
        //         $rightdata->status = 'Not Updated';
        //     }
        // }

        // return view('totalstation',compact('data','storedatas','rightdata'));
        $data = view('datatotalstation', compact('rightdata'))->render();
        $response['data'] = $data;
        return $response;
    }

    public function storepagerightforapp(Request $request)
    {
        // dd($request->all());
        $data = auth()->user();

        $storedataforapps = Forapprovalstation::all();
        $id = $request->id;
        $rightdata = Forapprovalstation::find($id);
        // dd($rightdata);

        // return view('totalstation',compact('data','storedatas','rightdata'));
        $data = view('datatotalstationforapproval', compact('rightdata'))->render();
        $response['data'] = $data;
        return $response;
    }

    public function updatesuperadminprofile(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
        ]);

        $data = User::find($request->id);

        if (request()->hasFile('profile_image')) {
            $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

            $request->profile_image->move('assets/images/', $img);
        } else {
            $img = $data->profile_image;
        }
        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->phone = $request->phone;
        $data->profile_image = $img;
        $data->save();

        return redirect()->back()->with('SUCCESS', 'User profile updated');
    }

    public function addnewstation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required',
            'store_name' => 'required',
            'store_location' => 'required',
            'city' => 'required',
            // 'opening_time' => 'required|date_format:H:i',
            // 'closing_time' => 'required|date_format:H:i',
        ], [
            'brand.required' => 'Please select brand name',
            'store_name.required' => 'Please enter store name',
            'store_location.required' => 'Please enter store location',
            'city.required' => 'Please enter city name',
            // 'opening_time.required' => 'Please enter opening time',
            // 'opening_time.date_format' => 'Please enter valid formate opening time',
            // 'closing_time.required' => 'Please enter closing time',
            // 'closing_time.date_format' => 'Please enter valid formate closing time',
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['error'=>$validator->errors()->all()]);
        // }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()]);
        }

        $brand = strtolower($request->brandother);

        if ($request->brand == 'other') {

            if ($request->brandother != null && $request->brandotherlogo != null) {


                if ($request->hasFile('brandotherlogo')) {

                    $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                    $request->brandotherlogo->move(public_path('images/brandlogo/'), $img);

                    $data = Brandlogo::create([
                        'brand' => $brand,
                        'brand_logo' => $img,
                    ]);
                } else {
                    // return redirect()->route('totalstation')->with('ERROR','Please select brand logo');
                    // return response()->json(['otherbrandlogo_error' => 'Please select brand logo']);

                    $brandtable = Brandlogo::where('brand',$brand)->first();

                    if($brandtable == null){
                        $data = Brandlogo::create([
                            'brand' => $brand,
                            'brand_logo' => 'logo.png',
                        ]);
                    }else{
                        return response()->json(['otherbrandalreadyadded' => 'This brand is already added.']);
                    }

                }
            } else {
                // return redirect()->route('totalstation')->with('ERROR','Please select brand logo');
                if ($request->brandother == null) {
                    return response()->json(['otherbrand_error' => 'Please enter brand name']);
                } else {
                    // return response()->json(['otherbrandlogo_error' => 'Please select brand logo']);

                    $brandtable = Brandlogo::where('brand',$brand)->first();

                    if($brandtable == null){
                        $data = Brandlogo::create([
                            'brand' => $brand,
                            'brand_logo' => 'logo.png',
                        ]);
                    }else{
                        return response()->json(['otherbrandalreadyadded' => 'This brand is already added.']);
                    }
                }
            }
        } else {
            $brand = $request->brand;
        }

        $user = auth()->user();
        // dd($row);

        //check already same pluscode address exist or not
        $coredata = Importdata::where('store_location', $request->store_location)->first();

        if ($coredata == null) {
            // location code convert into lat lon
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $client = new Client();
            $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json", [
                'query' => [
                    'address' => $request->store_location,
                    'key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            // dd($data);

            if ($data['status'] === 'OK') {
                $latitude = $data['results'][0]['geometry']['location']['lat'];
                $longitude = $data['results'][0]['geometry']['location']['lng'];
            } else {
                // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again..');
                return false;
            }
            //

            // get address
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            // $apiKey = '';
            $client = new Client();
            $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}");
            $datalocation = json_decode($response->getBody());

            if ($datalocation->status == 'OK') {
                // $store_address = $datalocation->results[0]->formatted_address;

                $cnt2 = count($datalocation->results);
                $store_address = null;

                for ($a = 0; $a < $cnt2; $a++) {
                    if (in_array('street_address', $datalocation->results[$a]->types)) {
                        $store_address = $datalocation->results[$a]->formatted_address;
                    }
                }
                if ($store_address == null) {
                    $store_address = $datalocation->results[0]->formatted_address;
                }
            } else {
                // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again..');
                // return response()->json(['error' => 'Sorry, something went wrong. Please try again..']);
                return false;
            }

            $gas_tit = $request->gas_tit;
            $gas_pri = $request->gas_pri;

            $cnt = count($gas_pri);
            $key = [];
            $val = [];
            for ($i = 0; $i < $cnt; $i++) {
                if ($gas_pri[$i] != null) {
                    $key[] = $gas_tit[$i];
                    $val[] = $gas_pri[$i];
                }
            }

            if (count($key) == 0 || count($val) == 0) {
                return response()->json(['gasoline_error' => 'Please add gasoline record']);
            }

            $gasoline = array_combine($key, $val);
            if (count($gasoline) > 0) {
                $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];
            } else {
                $gasoline = '';
                $forfil_price_gasoline = '';
            }



            $die_tit = $request->die_tit;
            $die_pri = $request->die_pri;

            $cnt2 = count($die_pri);
            $key2 = [];
            $val2 = [];
            for ($i = 0; $i < $cnt2; $i++) {
                if ($die_pri[$i] != null) {
                    $key2[] = $die_tit[$i];
                    $val2[] = $die_pri[$i];
                }
            }

            if (count($key2) == 0 || count($val2) == 0) {
                return response()->json(['diesel_error' => 'Please add diesel record']);
            }

            $diesel = array_combine($key2, $val2);
            if (count($diesel) > 0) {
                $forfil_price_diesel = $diesel[array_key_first($diesel)];
            } else {
                $diesel = '';
                $forfil_price_diesel = '';
            }




            //updated for brand logo after api and table
            $branddetail = Brandlogo::where('brand', strtolower($brand))->first();

            if ($branddetail != null) {
                $brand_logo = $branddetail->brand_logo;
            } else {
                $brand_logo = '';
            }

            //for web
            // if ($request->hasFile('store_image')) {

            //     $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

            //     //for curerent
            //     $request->store_image->move(public_path('images/storeimage/'), $img);

            //     // $img = url('images/storeimage/'.$img);
            //     $data = Storeimage::create([
            //         'store_name' => $request->store_name,
            //         'store_image' => $img,
            //     ]);
            // } else {
            //     $img = '';
            // }


            $data = Importdata::create([
                'user_id' => $user->id,
                'brand' => strtolower($brand),
                'store_name' => $request->store_name,
                'store_address' => $store_address,
                'city' => $request->city,
                // 'opening_time' => $request->opening_time,
                // 'closing_time' => $request->closing_time,
                'store_location' => $request->store_location,
                'store_location_latitude' => $latitude,
                'store_location_longitude' => $longitude,
                'diesel' => $diesel,
                'gasoline' => $gasoline,
                'landmarks' => $request->landmarks,
                'brand_logo' => $brand_logo,
                'store_image' => '',
                'forfil_price_diesel' => $forfil_price_diesel,
                'forfil_price_gasoline' => $forfil_price_gasoline,
                'custom' => now(),
            ]);
            // dd($data);


            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            $range = 'Testsheet'; // Change this to the desired sheet name.
            // $range = 'Live_Data'; // Change this to the desired sheet name.

            if ($gasoline != null) {
                foreach ($gasoline as $key1 => $value1) {
                    $result1[] = $key1 . '=' . $value1;
                }
                $gasoline = implode(',', $result1);
            } else {
                $gasoline = '';
            }


            if ($diesel != null) {
                foreach ($diesel as $key2 => $value2) {
                    $result2[] = $key2 . '=' . $value2;
                }
                $diesel = implode(',', $result2);
            } else {
                $diesel = '';
            }

            $status = 'Updated';

            $values[] = [$brand, $request->store_name, $request->store_location, $request->city, $gasoline, $diesel, $request->landmarks, $status]; // Assuming you're sending data in the request.

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values,
            ]);

            $params = [
                'valueInputOption' => 'RAW',
            ];

            // Get the last row number in the sheet
            $lastRow = count($service->spreadsheets_values->get($spreadsheetId, $range)->getValues());

            // Set the range to append data to the last row
            $range = 'Testsheet!A' . ($lastRow + 1);
            // $range = 'Live_Data!A' . ($lastRow + 1);

            $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
        } else {
            // return redirect()->back()->with('ERROR', 'This location has been already added.');
            return response()->json(['alreadyaddederror' => 'This location has been already added.']);
            // return false;
        }


        // return redirect()->route('totalstation')->with('SUCCESS', 'Data added successfully');
        // return true;
        return response()->json(['new' => $data]);
    }

    public function updatestationdata(Request $request)
    {
        // dd($request->all());
        // $validate = $request->validate([
        //     'id' => 'required',
        //     'store_name' => 'required',
        //     'store_address' => 'required',
        //     'opening_time' => 'required|date_format:H:i',
        //     'closing_time' => 'required|date_format:H:i|after:opening_time',
        //     'landmarks' => 'required',
        // ]);
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'brand' => 'required',
            'store_location' => 'required',
            'store_name' => 'required',
            'store_address' => 'required',
            // 'opening_time' => 'required|date_format:H:i',
            // 'closing_time' => 'required|date_format:H:i',
            'gasolinet' => 'required',
            'gasolinep' => 'required',
            'dieselt' => 'required',
            'dieselp' => 'required',
            'status' => 'required',
            'city' => 'required',
        ], [
            'id.required' => 'Id not received',
            'brand.required' => 'Brand name not received',
            'store_location.required' => 'Store location not received',
            'store_name.required' => 'Please enter store name',
            'store_address.required' => 'Please enter store address',
            // 'opening_time.required' => 'Please enter opening time',
            // 'opening_time.date_format' => 'Please enter valid formate opening time',
            'closing_time.required' => 'Please enter closing time',
            'closing_time.date_format' => 'Please enter valid formate closing time',
            'gasolinet.required' => 'Please enter gasoline title',
            'gasolinep.required' => 'Please enter gasoline price',
            'dieselt.required' => 'Please enter diesel title',
            'dieselp.required' => 'Please enter diesel price',
            'status.required' => 'Please select status',
            'city.required' => 'Please enter city name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()]);
        }

        $stationdata = Importdata::find($request->id);

        if ($stationdata == null) {
            // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again.');
            // return response()->json(['error' => 'Sorry, something went wrong. Please try again.']);
            return false;
        }

        $stationdata->store_name = $request->store_name;
        $stationdata->store_address = $request->store_address;
        // $stationdata->opening_time = $request->opening_time;
        // $stationdata->closing_time = $request->closing_time;

        $store_location = $request->store_location;

        if ($request->hasFile('store_image')) {

            $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

            //for curerent
            $request->store_image->move(public_path('images/storeimage/'), $img);

            // $img = url('images/storeimage/'.$img);
            $data = Storeimage::create([
                'store_name' => $request->store_name,
                'store_image' => $img,
            ]);

            $stationdata->store_image = $img;
        }



        if ($request->openlocationcode != null) {

            $coredata = Importdata::where('store_location', $request->openlocationcode)->first();

            if ($coredata == null) {
                $stationdata->store_address = $request->store_address;
                $stationdata->store_location_latitude = $request->store_location_latitude;
                $stationdata->store_location_longitude = $request->store_location_longitude;
                $store_location = $request->openlocationcode;
            } else {
                return response()->json(['alreadyaddederror' => 'This location has been already added.']);
            }
        }


        $gasolinet = $request->gasolinet;
        $gasolinep = $request->gasolinep;

        $cnt = count($gasolinep);
        $key = [];
        $val = [];
        for ($i = 0; $i < $cnt; $i++) {
            if ($gasolinep[$i] != null) {
                $key[] = $gasolinet[$i];
                $val[] = $gasolinep[$i];
            }
        }

        if (count($key) == 0 || count($val) == 0) {
            return response()->json(['gas_upd_error' => 'Please enter gasoline title and price']);
        }

        $gasoline = array_combine($key, $val);
        if (count($gasoline) > 0) {
            $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];
        } else {
            $gasoline = '';
            $forfil_price_gasoline = '';
        }

        $dieselt = $request->dieselt;
        $dieselp = $request->dieselp;

        $cnt2 = count($dieselp);
        $key2 = [];
        $val2 = [];
        for ($i = 0; $i < $cnt2; $i++) {
            if ($dieselp[$i] != null) {
                $key2[] = $dieselt[$i];
                $val2[] = $dieselp[$i];
            }
        }

        if (count($key2) == 0 || count($val2) == 0) {
            return response()->json(['die_upd_error' => 'Please enter diesel title and price']);
        }

        $diesel = array_combine($key2, $val2);
        if (count($diesel) > 0) {
            $forfil_price_diesel = $diesel[array_key_first($diesel)];
        } else {
            $diesel = '';
            $forfil_price_diesel = '';
        }


        // for update_at
        foreach ($stationdata->gasoline as $key1 => $value1) {
            $r1[] = $key1 . '=' . $value1;
        }
        $g = implode(',', $r1);
        foreach ($gasoline as $key2 => $value2) {
            $r2[] = $key2 . '=' . $value2;
        }
        $g2 = implode(',', $r2);
        if($g != $g2) {
            $stationdata->custom = now();
        }


        foreach ($stationdata->diesel as $key3 => $value3) {
            $r3[] = $key3 . '=' . $value3;
        }
        $d = implode(',', $r3);
        foreach ($diesel as $key4 => $value4) {
            $r4[] = $key4 . '=' . $value4;
        }
        $d2 = implode(',', $r4);
        if($d != $d2) {
            $stationdata->custom = now();
        }
        //

        $stationdata->gasoline = $gasoline;
        $stationdata->forfil_price_gasoline = $forfil_price_gasoline;
        $stationdata->diesel = $diesel;
        $stationdata->forfil_price_diesel = $forfil_price_diesel;

        $stationdata->landmarks = $request->landmarks;
        $stationdata->status = $request->status;
        $stationdata->city = $request->city;


        // dd($gasoline);
        // $difference = array_diff($stationdata->gasoline, $gasoline);

        // $st1 = implode(',',$stationdata->gasoline);
        // $st2 = implode(',',$gasoline);
        // dd($st1);

        // if($request->status == 'Not Updated'){
        //     $stationdata->status = $request->landmarks;
        // }else{
        //     $st = Notupdstation::where('station_id',$stationdata->id)->first();
        //     if($st != null){
        //         $st->delete();
        //     }
        // }



        //for update data
        $client = new Google_Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);

        $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
        //$range = 'A2:H4'; // Change this to the desired range.
        // $range = 'Live_Data'; // Change this to the desired range.
        $range = 'Testsheet'; // Change this to the desired range.

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        if ($values == null) {
            // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again.');
            // return response()->json(['error' => 'Sorry, something went wrong. Please try again.']);
            return false;
        }

        // dd($values);
        $loc = $stationdata->store_location;

        $cnt = count($values);
        // dd($cnt);

        for ($i = 0; $i < $cnt; $i++) {
            // dd($values[$i]);
            if ($values[$i]['2'] == $loc) {
                $data2 = $values[$i];
                $rowIndex = $i;
            }
        }

        // dd($data2);
        // dd($rowIndex + 1);

        if ($gasoline != null) {
            foreach ($gasoline as $key1 => $value1) {
                $result1[] = $key1 . '=' . $value1;
            }
            $gasoline = implode(',', $result1);
        } else {
            $gasoline = '';
        }


        if ($diesel != null) {
            foreach ($diesel as $key2 => $value2) {
                $result2[] = $key2 . '=' . $value2;
            }
            $diesel = implode(',', $result2);
        } else {
            $diesel = '';
        }

        $values[$rowIndex] = [$request->brand, $request->store_name, $store_location, $request->city, $gasoline, $diesel, $request->landmarks, $request->status];
        // dd($values);
        // Prepare the updated data
        $updateData = new Google_Service_Sheets_ValueRange();
        $updateData->setValues($values);

        // Update the data in the sheet
        $updateRange = $range;
        $updateOptions = ['valueInputOption' => 'RAW'];
        $service->spreadsheets_values->update($spreadsheetId, $updateRange, $updateData, $updateOptions);


        $stationdata->store_location = $store_location;
        $stationdata->save();


        //

        // $firebaseToken = Devicetoken::whereNotNull('device_token')->pluck('device_token')->all();
        // // dd($firebaseToken);
        // $SERVER_API_KEY = env('FCM_SERVER_KEY');

        // $data = [
        //     "registration_ids" => $firebaseToken,
        //     "notification" => [
        //         "title" => 'Update',
        //         "body" => "Update station successfully",
        //     ]
        // ];
        // $dataString = json_encode($data);

        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);

        //

        // return redirect()->route('totalstation')->with('SUCCESS', 'Data update successfully');
        // return true;
        return response()->json(['stationdata' => $stationdata]);
    }

    public function deletestationdata(Request $request)
    {
        $id = $request->id;

        if ($id == null) {
            // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again..');
            return false;
        } else {
            $data = Importdata::find($id);


            //for update data
            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            //$range = 'A2:H4'; // Change this to the desired range.
            // $range = 'Live_Data'; // Change this to the desired range.
            $range = 'Testsheet'; // Change this to the desired range.

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);

            $values = $response->getValues();

            if ($values == null) {
                // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again..');
                return false;
            }

            $loc = $data->store_location;

            $ct = count($values);
            // dd($cnt);

            for ($i = 0; $i < $ct; $i++) {
                // dd($values[$i]);
                if ($values[$i]['2'] == $loc) {
                    $data2 = $values[$i];
                    $rowIndex = $i;
                }
            }

            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            $sheetTitle = 'Testsheet'; // Change this to the desired sheet name.
            // $sheetTitle = 'Live_Data'; // Change this to the desired sheet name.

            $rowToDelete = $rowIndex + 1; // Assuming you send the row number in the request.

            $sheets = $service->spreadsheets->get($spreadsheetId)->getSheets();
            $sheetId = $this->getSheetId($sheets, $sheetTitle);

            if ($sheetId !== null) {
                $requests = [
                    new Google_Service_Sheets_Request([
                        'deleteDimension' => [
                            'range' => [
                                'sheetId' => $sheetId,
                                'dimension' => 'ROWS',
                                'startIndex' => $rowToDelete - 1,
                                'endIndex' => $rowToDelete,
                            ],
                        ],
                    ]),
                ];

                $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                    'requests' => $requests,
                ]);

                $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
            }

            $data->delete();
            // return response()->json(['success' => 'true', 'message' => 'Delete data successfully'], 200);
            // return redirect()->route('totalstation')->with('SUCCESS', 'Delete data successfully');
            return true;
        }
    }

    public function sync()
    {
        $client = new Google_Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);

        $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
        // $range = 'A2:H2'; // Change this to the desired range.
        // $range = 'Live_Data'; // Change this to the desired range.
        $range = 'Testsheet'; // Change this to the desired range.

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        // dd($values);

        if ($values == null) {
            return redirect()->back()->with('ERRORR', 'Sorry, something went wrong. Please try again.');
        }

        $cnt = count($values);

        for ($i = 1; $i < $cnt; $i++) {
            // print_r($values[$i]);
            $user = auth()->user();

            $coredata = Importdata::where('store_location', $values[$i][2])->first();

            if ($coredata == null) {
                // location code convert into lat lon
                $apiKey = env('GOOGLE_MAPS_API_KEY');
                $client = new Client();
                $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json", [
                    'query' => [
                        'address' => $values[$i][2],
                        'key' => $apiKey,
                    ],
                ]);

                $data = json_decode($response->getBody(), true);
                // dd($data);

                if ($data['status'] === 'OK') {
                    $latitude = $data['results'][0]['geometry']['location']['lat'];
                    $longitude = $data['results'][0]['geometry']['location']['lng'];
                } else {
                    return redirect()->back()->with('ERRORR', 'Sorry, something went wrong. Please try again.');
                }
                //

                // get address
                $apiKey = env('GOOGLE_MAPS_API_KEY');
                // $apiKey = '';
                $client = new Client();
                $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}");
                $datalocation = json_decode($response->getBody());
                if ($datalocation->status == 'OK') {
                    // $store_address = $datalocation->results[0]->formatted_address;

                    $cnt2 = count($datalocation->results);
                    $store_address = null;

                    for ($a = 0; $a < $cnt2; $a++) {
                        if (in_array('street_address', $datalocation->results[$a]->types)) {
                            $store_address = $datalocation->results[$a]->formatted_address;
                        }
                    }
                    if ($store_address == null) {
                        $store_address = $datalocation->results[0]->formatted_address;
                    }
                } else {
                    // return response()->json(['success' => 'false', 'message' => 'Sorry, something went wrong. Please try again.'], 404);
                    return redirect()->back()->with('ERRORR', 'Sorry, something went wrong. Please try again.');
                }

                //diesel and gasoline data save code
                if ($values[$i][5] != null) {
                    $diesel = [];
                    $values[$i][5] = rtrim($values[$i][5], ',');
                    foreach (explode(',', $values[$i][5]) as $item) {
                        $parts = explode('=', $item);
                        $diesel[trim($parts[0])] = $parts[1];
                    }

                    //remove null
                    foreach ($diesel as $key1 => $value1) {
                        if (is_null($value1) || $value1 == '') {
                            $diesel[$key1] = '0';
                        }
                        // unset($diesel[$key1]);
                    }

                    //for filter set first in other field
                    $forfil_price_diesel = $diesel[array_key_first($diesel)];
                } else {
                    $diesel = '';
                    $forfil_price_diesel = '';
                }

                if ($values[$i][4] != null) {
                    $gasoline = [];
                    $values[$i][4] = rtrim($values[$i][4], ',');
                    foreach (explode(',', $values[$i][4]) as $item2) {
                        $parts2 = explode('=', $item2);
                        $gasoline[trim($parts2[0])] = $parts2[1];
                    }

                    //remove null
                    foreach ($gasoline as $key2 => $value2) {
                        if (is_null($value2) || $value2 == '') {
                            $gasoline[$key2] = '0';
                        }
                        // unset($gasoline[$key2]);
                    }

                    //for filter set first in other field
                    $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];
                } else {
                    $gasoline = '';
                    $forfil_price_gasoline = '';
                }



                //updated for brand logo after api and table
                $branddetail = Brandlogo::where('brand', strtolower($values[$i][0]))->first();

                if ($branddetail != null) {
                    $brand_logo = $branddetail->brand_logo;
                } else {
                    $brand_logo = '';
                }

                //for store image
                $storedetail = Storeimage::where('store_name', $values[$i][1])->first();

                if ($storedetail != null) {
                    $store_image = $storedetail->store_image;
                } else {
                    $store_image = '';
                }

                // dump($values[$i][1]);
                $data = Importdata::create([
                    'user_id' => $user->id,
                    'brand' => strtolower($values[$i][0]),
                    'store_name' => $values[$i][1],
                    'store_address' => $store_address,
                    // 'opening_time' => $values[$i][3],
                    // 'closing_time' => $values[$i][4],
                    'city' => $values[$i][3],
                    'store_location' => $values[$i][2],
                    'store_location_latitude' => $latitude,
                    'store_location_longitude' => $longitude,
                    'diesel' => $diesel,
                    'gasoline' => $gasoline,
                    'landmarks' => isset($values[$i][6]) ? $values[$i][6] : null,
                    'brand_logo' => $brand_logo,
                    'store_image' => $store_image,
                    'forfil_price_diesel' => $forfil_price_diesel,
                    'forfil_price_gasoline' => $forfil_price_gasoline,
                    'custom' => now(),
                    'status' => $values[$i][7],
                ]);
            } else {
                // koi field ni value ma changes hshe ae krva
                // aevu lage ae check kravya baki na direct
                // store location ma koi changes nai thai

                if ($coredata->brand != $values[$i][0]) {
                    $coredata->brand = strtolower($values[$i][0]);
                }

                //updated for brand logo after api and table
                $branddetail = Brandlogo::where('brand', strtolower($values[$i][0]))->first();

                if ($branddetail != null) {
                    $brand_logo = $branddetail->brand_logo;
                } else {
                    $brand_logo = '';
                }

                //for store image
                $storedetail = Storeimage::where('store_name', $values[$i][1])->first();

                if ($storedetail != null) {
                    $store_image = $storedetail->store_image;
                } else {
                    $store_image = '';
                }

                $coredata->brand_logo = $brand_logo;

                $coredata->store_image = $store_image;

                $coredata->store_name = $values[$i][1];

                // $coredata->opening_time = $values[$i][3];
                // $coredata->closing_time = $values[$i][4];
                $coredata->city = $values[$i][3];

                //diesel and gasoline
                if ($values[$i][5] != null) {
                    $diesel = [];
                    $values[$i][5] = rtrim($values[$i][5], ',');
                    foreach (explode(',', $values[$i][5]) as $item) {
                        $parts = explode('=', $item);
                        $diesel[trim($parts[0])] = $parts[1];
                    }

                    //remove null
                    foreach ($diesel as $key1 => $value1) {
                        if (is_null($value1) || $value1 == '') {
                            $diesel[$key1] = '0';
                        }
                        // unset($diesel[$key1]);
                    }

                    //for filter set first in other field
                    $forfil_price_diesel = $diesel[array_key_first($diesel)];
                } else {
                    $diesel = '';
                    $forfil_price_diesel = '';
                }

                if ($values[$i][4] != null) {
                    $gasoline = [];
                    $values[$i][4] = rtrim($values[$i][4], ',');
                    foreach (explode(',', $values[$i][4]) as $item2) {
                        $parts2 = explode('=', $item2);
                        $gasoline[trim($parts2[0])] = $parts2[1];
                    }

                    //remove null
                    foreach ($gasoline as $key2 => $value2) {
                        if (is_null($value2) || $value2 == '') {
                            $gasoline[$key2] = '0';
                        }
                        // unset($gasoline[$key2]);
                    }

                    //for filter set first in other field
                    $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];
                } else {
                    $gasoline = '';
                    $forfil_price_gasoline = '';
                }


                //for update_at
                foreach ($coredata->gasoline as $key1 => $value1) {
                    $r1[] = $key1 . '=' . $value1;
                }
                $g = implode(',', $r1);
                foreach ($gasoline as $key2 => $value2) {
                    $r2[] = $key2 . '=' . $value2;
                }
                $g2 = implode(',', $r2);
                // dd($gasoline);
                if($g != $g2) {
                    $coredata->custom = now();
                }


                foreach ($coredata->diesel as $key3 => $value3) {
                    $r3[] = $key3 . '=' . $value3;
                }
                $d = implode(',', $r3);
                foreach ($diesel as $key4 => $value4) {
                    $r4[] = $key4 . '=' . $value4;
                }
                $d2 = implode(',', $r4);
                if($d != $d2) {
                    $coredata->custom = now();
                }




                $coredata->diesel = $diesel;
                $coredata->gasoline = $gasoline;


                $coredata->forfil_price_diesel = $forfil_price_diesel;
                $coredata->forfil_price_gasoline = $forfil_price_gasoline;

                $coredata->landmarks = isset($values[$i][6]) ? $values[$i][6] : null;
                $coredata->status = $values[$i][7];

                $coredata->save();
            }
        }


        //deleted record of spreadsheet remove in database code
        $totaldb = Importdata::all();

        foreach ($totaldb as $totdb) {
            $locationExists = false;
            // dd($totdb);
            for ($i = 1; $i < $cnt; $i++) {
                if ($values[$i][2] === $totdb->store_location) {
                    $locationExists = true;
                    break;
                }
            }

            if ($locationExists) {
            } else {
                $totdb->delete();
            }
        }

        // return response()->json(['success' => 'true', 'message' => 'Data synced with google sheet successfully'], 200);
        return redirect()->route('index')->with('SUCCESS', 'Data has been synced with Google Sheet.');
    }

    public function getbrandlogopor(Request $request)
    {
        $logo = Brandlogo::where('brand', $request->brand)->first();

        return $logo;
    }

    public function brandsmanagement()
    {
        $userdata = auth()->user();

        $branddatas = Brandlogo::all();
        return view('brandmanagement', compact('userdata', 'branddatas'));
    }

    public function addnewbrandfromportal(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'brand' => 'required',
            'brand_logo' => 'required',
        ]);

        $brand = strtolower($request->brand);
        // dd($brand);

        $brandtable = Brandlogo::where('brand', $brand)->first();

        if ($brandtable == null) {
            if ($request->hasFile('brand_logo')) {

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->brand_logo->move(public_path('images/brandlogo/'), $img);
            } else {
                // return response()->json(['success' => 'false', 'message' => 'Sorry, something went wrong. Please try again.'], 404);
                return redirect()->route('brandsmanagement')->with('ERROR', 'Sorry, something went wrong. Please try again.');
            }

            $data = Brandlogo::create([
                'brand' => $brand,
                'brand_logo' => $img,
            ]);

            // return response()->json(['success' => 'true', 'message' => 'Brand logo added successfully','data'=>$data], 200);
            return redirect()->route('brandsmanagement')->with('SUCCESS', 'Brand logo has been added.');
        } else {
            return redirect()->route('brandsmanagement')->with('ERROR', 'This brand logo has been already added.');
        }
    }

    public function editnewbrandfromportal(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'brand' => 'required',
            'brand_logo' => 'required',
        ]);


        $data = Brandlogo::find($request->id);

        $brand = strtolower($request->brand);

        if($data->brand == $brand){
            if ($request->hasFile('brand_logo')) {

                if ($data->brand_logo != 'logo.png') {
                    if (file_exists(public_path('images/brandlogo/' . $data->brand_logo))) {

                        @unlink(public_path('images/brandlogo/' . $data->brand_logo));
                    }
                }

                $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                $request->brand_logo->move(public_path('images/brandlogo/'), $img);
                $data->brand_logo = $img;

                $maindatas = Importdata::where('brand', $data->brand)->get();

                foreach ($maindatas as $maindata) {
                    // $maindata->brand = $brand;
                    $maindata->brand_logo = $img;
                    $maindata->save();
                }
            } else {
                $data->brand_logo = $request->brand_logo;
            }

            // $data->brand = $brand;
            $data->save();
        }else{
            $ex = Brandlogo::where('brand',$brand)->first();

            if($ex == null){
                if ($request->hasFile('brand_logo')) {

                    if ($data->brand_logo != 'logo.png') {
                        if (file_exists(public_path('images/brandlogo/' . $data->brand_logo))) {

                            @unlink(public_path('images/brandlogo/' . $data->brand_logo));
                        }
                    }

                    $img = 'IMG' . '.' . rand('1111', '9999') . time() . '.' . 'png';

                    $request->brand_logo->move(public_path('images/brandlogo/'), $img);
                    $data->brand_logo = $img;

                } else {
                    $img = $request->brand_logo;
                }

                $maindatas = Importdata::where('brand', $data->brand)->get();
                foreach ($maindatas as $maindata) {
                    $maindata->brand = $brand;
                    $maindata->brand_logo = $img;

                     //for update data
                    $client = new Google_Client();
                    $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
                    $client->addScope(Google_Service_Sheets::SPREADSHEETS);

                    $service = new Google_Service_Sheets($client);

                    $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
                    //$range = 'A2:H4'; // Change this to the desired range.
                    // $range = 'Live_Data'; // Change this to the desired range.
                    $range = 'Testsheet'; // Change this to the desired range.

                    $response = $service->spreadsheets_values->get($spreadsheetId, $range);

                    $values = $response->getValues();

                    if ($values == null) {
                        // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again.');
                        // return response()->json(['error' => 'Sorry, something went wrong. Please try again.']);
                        return 'problem';
                    }

                    // dd($values);
                    $loc = $maindata->store_location;

                    $cnt = count($values);
                    // dd($cnt);

                    for ($i = 0; $i < $cnt; $i++) {
                        // dd($values[$i]);
                        if ($values[$i]['2'] == $loc) {
                            $data2 = $values[$i];
                            $rowIndex = $i;
                        }
                    }



                    $values[$rowIndex] = [$brand];
                    // dd($values);
                    // Prepare the updated data
                    $updateData = new Google_Service_Sheets_ValueRange();
                    $updateData->setValues($values);

                    // Update the data in the sheet
                    $updateRange = $range;
                    $updateOptions = ['valueInputOption' => 'RAW'];
                    $service->spreadsheets_values->update($spreadsheetId, $updateRange, $updateData, $updateOptions);

                    $maindata->save();
                }

                $data->brand = $brand;
                $data->save();
            }else{
                // return redirect()->route('brandsmanagement')->with('ERROR', 'This brand has been already added.');
                return false;
            }
        }

        // return redirect()->route('brandsmanagement')->with('SUCCESS', 'Brand has been updated.');
        return true;
    }

    public function removebrandfromportal(Request $request)
    {
        $removeid = $request->id;

        $removedata = Brandlogo::find($removeid);

        $maindata = Importdata::where('brand',$removedata->brand)->first();

        if($maindata == null){

            if ($removedata->brand_logo != 'logo.png') {
                if (file_exists(public_path('images/brandlogo/' . $removedata->brand_logo))) {

                    @unlink(public_path('images/brandlogo/' . $removedata->brand_logo));
                }
            }

            $removedata->delete();

            // return redirect()->route('brandsmanagement')->with('SUCCESS', 'Brand has been deleted.');
            return true;
        }else{
            // return redirect()->route('brandsmanagement')->with('ERROR', 'You have already added station with this brand. So you can not delete this brand.');
            return false;
        }

    }

    public function priceforapproval()
    {
        $userdata = auth()->user();

        $fulldatas = Pricechangereq::with('storedata')->get();
        // dd($fulldatas);
        return view('priceforapproval', compact('userdata', 'fulldatas'));
    }

    public function rejectpricereq(Request $request)
    {
        // dd($id);
        $data = Pricechangereq::find($request->id);

        if ($data == null) {
            // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
            return false;
        } else {
            if (file_exists(public_path('images/priceapproval/' . $data->detail_photo))) {

                @unlink(public_path('images/priceapproval/' . $data->detail_photo));
            }

            $data->delete();
        }

        // return redirect()->route('priceforapproval')->with('SUCCESS','Reject request successfully');
        return true;
    }

    public function approvepricereq(Request $request)
    {
        $data = Pricechangereq::find($request->id);
        // dd($data);
        if ($data == null) {
            // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
            return false;
        } else {
            $maindata = Importdata::find($data->station_id);

            if ($maindata == null) {
                // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
                return false;
            } else {
                $maindata->diesel = $data->diesel;
                $maindata->forfil_price_diesel = $data->diesel[array_key_first($data->diesel)];


                $maindata->gasoline = $data->gasoline;
                $maindata->forfil_price_gasoline = $data->gasoline[array_key_first($data->gasoline)];
                $maindata->modify_name = $data->modify_name;
                $maindata->status = 'Updated';
                $maindata->custom = now();

                $maindata->save();

                //for update data
                $client = new Google_Client();
                $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
                $client->addScope(Google_Service_Sheets::SPREADSHEETS);

                $service = new Google_Service_Sheets($client);

                $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
                //$range = 'A2:H4'; // Change this to the desired range.
                // $range = 'Live_Data'; // Change this to the desired range.
                $range = 'Testsheet'; // Change this to the desired range.

                $response = $service->spreadsheets_values->get($spreadsheetId, $range);

                $values = $response->getValues();

                if ($values == null) {
                    // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
                    return false;
                }

                // dd($values);
                $loc = $maindata->store_location;

                $cnt = count($values);
                // dd($cnt);

                for ($i = 0; $i < $cnt; $i++) {
                    // dd($values[$i]);
                    if ($values[$i]['2'] == $loc) {
                        $data2 = $values[$i];
                        $rowIndex = $i;
                    }
                }

                // dd($data2);
                // dd($rowIndex + 1);

                if ($data->gasoline != null) {
                    foreach ($data->gasoline as $key1 => $value1) {
                        $result1[] = $key1 . '=' . $value1;
                    }
                    $gasoline = implode(',', $result1);
                } else {
                    $gasoline = '';
                }


                if ($data->diesel != null) {
                    foreach ($data->diesel as $key2 => $value2) {
                        $result2[] = $key2 . '=' . $value2;
                    }
                    $diesel = implode(',', $result2);
                } else {
                    $diesel = '';
                }

                $values[$rowIndex] = [$maindata->brand, $maindata->store_name, $maindata->store_location, $maindata->city, $gasoline, $diesel, $maindata->landmarks];
                // dd($values);
                // Prepare the updated data
                $updateData = new Google_Service_Sheets_ValueRange();
                $updateData->setValues($values);

                // Update the data in the sheet
                $updateRange = $range;
                $updateOptions = ['valueInputOption' => 'RAW'];
                $service->spreadsheets_values->update($spreadsheetId, $updateRange, $updateData, $updateOptions);

                if (file_exists(public_path('images/priceapproval/' . $data->detail_photo))) {

                    @unlink(public_path('images/priceapproval/' . $data->detail_photo));
                }

                if($data->user_id != '0'){
                    $user = User::find($data->user_id);
                    $user->points = $user->points + 10;
                    $user->save();
                }

                $data->delete();
            }
        }

        // return redirect()->route('priceforapproval')->with('SUCCESS','Approve request successfully');
        return true;
    }

    public function updatepriceforappreq(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'modify_name' => 'required',
            'gasolinet' => 'required',
            'gasolinep' => 'required',
            'dieselt' => 'required',
            'dieselp' => 'required',
            'comments' => 'required',
        ], [
            'id.required' => 'Id not received',
            'modify_name.required' => 'Please enter requested person name',
            'gasolinet.required' => 'Please enter gasoline title',
            'gasolinep.required' => 'Please enter gasoline price',
            'dieselt.required' => 'Please enter diesel title',
            'dieselp.required' => 'Please enter diesel price',
            'comments.required' => 'Please enter feedback/commnets',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()]);
        }

        $pricereqdata = Pricechangereq::find($request->id);

        if ($pricereqdata == null) {
            // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again.');
            // return response()->json(['error' => 'Sorry, something went wrong. Please try again.']);
            return false;
        }

        $pricereqdata->modify_name = $request->modify_name;
        $pricereqdata->comments = $request->comments;

        $gasolinet = $request->gasolinet;
        $gasolinep = $request->gasolinep;

        $cnt = count($gasolinep);
        $key = [];
        $val = [];
        for ($i = 0; $i < $cnt; $i++) {
            if ($gasolinep[$i] != null) {
                $key[] = $gasolinet[$i];
                $val[] = $gasolinep[$i];
            }
        }

        if (count($key) == 0 || count($val) == 0) {
            return response()->json(['gas_upd_error' => 'Please enter gasoline title and price']);
        }

        $gasoline = array_combine($key, $val);

        $dieselt = $request->dieselt;
        $dieselp = $request->dieselp;

        $cnt2 = count($dieselp);
        $key2 = [];
        $val2 = [];
        for ($i = 0; $i < $cnt2; $i++) {
            if ($dieselp[$i] != null) {
                $key2[] = $dieselt[$i];
                $val2[] = $dieselp[$i];
            }
        }

        if (count($key2) == 0 || count($val2) == 0) {
            return response()->json(['die_upd_error' => 'Please enter diesel title and price']);
        }

        $diesel = array_combine($key2, $val2);


        $pricereqdata->gasoline = $gasoline;
        $pricereqdata->diesel = $diesel;
        $pricereqdata->save();

        return response()->json(['stationdata' => $pricereqdata]);
    }

    private function getSheetId($sheets, $sheetTitle)
    {
        foreach ($sheets as $sheet) {
            if ($sheet->properties->title == $sheetTitle) {
                return $sheet->properties->sheetId;
            }
        }

        return null;
    }

    public function priceforapppageright(Request $request)
    {
        // $data = auth()->user();

        // $storedatas = Importdata::all();
        $id = $request->id;
        // dd($id);
        // $rightdatapriceapp = Pricechangereq::with('storedata')->find($id);
        $rightdatapriceapp = Pricechangereq::with('storedata')->find($id);
        // dd($rightdatapriceapp);

        // return view('totalstation',compact('data','storedatas','rightdata'));
        $data = view('datapriceforapproval', compact('rightdatapriceapp'))->render();
        $response['data'] = $data;
        return $response;
    }

    public function need_approval_change(Request $request)
    {
        $id = $request->id;
        $need_approval = $request->need_approval;

        $data = User::find($id);

        if ($data != null) {
            $data->need_approval = $need_approval;
            $data->save();
            return true;
        } else {
            return false;
        }
    }

    public function rejectforapprovastation(Request $request)
    {
        $id = $request->id;

        $data = Forapprovalstation::find($id);

        if ($data != null) {
            $data->delete();
            return true;
        } else {

            return false;
        }
    }

    public function approveforapprovastation(Request $request)
    {
        $forappstation = Forapprovalstation::find($request->id);
        // dd($forappstation->diesel);

        if ($forappstation->city == null) {
            return false;
        }


        //add brand in brandtable
        $branddetail = Brandlogo::where('brand', strtolower($forappstation->brand))->first();
        if($branddetail == null){

            if($forappstation->status == '1'){

                if (file_exists(public_path('images/brandlogo/' . $forappstation->brand_logo))) {

                    @unlink(public_path('images/brandlogo/' . $forappstation->brand_logo));
                }

                $data = Brandlogo::create([
                    'brand' => strtolower($forappstation->brand),
                    'brand_logo' => 'logo.png',
                ]);
                $brand_logo = 'logo.png';
            }else{
                $data = Brandlogo::create([
                    'brand' => strtolower($forappstation->brand),
                    'brand_logo' => $forappstation->brand_logo,
                ]);

                $brand_logo = $forappstation->brand_logo;
            }

        }else{
            $brand_logo = $branddetail->brand_logo;
        }


        $data = Importdata::create([
            'user_id' => $forappstation->user_id,
            'brand' => $forappstation->brand,
            'store_name' => $forappstation->store_name,
            'store_address' => $forappstation->store_address,
            // 'opening_time' => $forappstation->opening_time,
            // 'closing_time' => $forappstation->closing_time,
            'city' => $forappstation->city,
            'store_location' => $forappstation->store_location,
            'store_location_latitude' => $forappstation->store_location_latitude,
            'store_location_longitude' => $forappstation->store_location_longitude,
            'diesel' => $forappstation->diesel,
            'gasoline' => $forappstation->gasoline,
            'landmarks' => $forappstation->landmarks,
            'brand_logo' => $brand_logo,
            'store_image' => $forappstation->store_image,
            'forfil_price_diesel' => $forappstation->forfil_price_diesel,
            'forfil_price_gasoline' => $forappstation->forfil_price_gasoline,
            'modify_name' => $forappstation->nick_name,
            'custom' => now(),
        ]);

        if($data->user_id != '0'){
            $us = User::find($data->user_id);
            $us->points = $us->points + 10;
            $us->save();
        }

        $client = new Google_Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);

        $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
        $range = 'Testsheet'; // Change this to the desired sheet name.
        // $range = 'Live_Data'; // Change this to the desired sheet name.

        if ($forappstation->gasoline != null) {
            foreach ($forappstation->gasoline as $key1 => $value1) {
                $result1[] = $key1 . '=' . $value1;
            }
            $gasoline = implode(',', $result1);
        } else {
            $gasoline = '';
        }


        if ($forappstation->diesel != null) {
            foreach ($forappstation->diesel as $key2 => $value2) {
                $result2[] = $key2 . '=' . $value2;
            }
            $diesel = implode(',', $result2);
        } else {
            $diesel = '';
        }

        $status = 'Updated';

        $values[] = [$forappstation->brand, $forappstation->store_name, $forappstation->store_location, $forappstation->city, $gasoline, $diesel, $forappstation->landmarks, $status]; // Assuming you're sending data in the request.

        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values,
        ]);

        $params = [
            'valueInputOption' => 'RAW',
        ];

        // Get the last row number in the sheet
        $lastRow = count($service->spreadsheets_values->get($spreadsheetId, $range)->getValues());

        // Set the range to append data to the last row
        $range = 'Testsheet!A' . ($lastRow + 1);
        // $range = 'Live_Data!A' . ($lastRow + 1);

        $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

        $forappstation->delete();

        // return true;
        return response()->json(['data' => $data]);
    }

    public function updateforapprovalstationdata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'store_name' => 'required',
            'nick_name' => 'required',
            'store_address' => 'required',
            'gasolinet' => 'required',
            'gasolinep' => 'required',
            'dieselt' => 'required',
            'dieselp' => 'required',
            'city' => 'required',
        ], [
            'id.required' => 'Id not received',
            'store_name.required' => 'Please enter store name',
            'nick_name.required' => 'Please enter nick name',
            'store_address.required' => 'Please enter store address',
            'gasolinet.required' => 'Please enter gasoline title',
            'gasolinep.required' => 'Please enter gasoline price',
            'dieselt.required' => 'Please enter diesel title',
            'dieselp.required' => 'Please enter diesel price',
            'city.required' => 'Please enter city name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()]);
        }

        $stationdata = Forapprovalstation::find($request->id);

        if ($stationdata == null) {
            // return redirect()->route('totalstation')->with('ERROR', 'Sorry, something went wrong. Please try again.');
            // return response()->json(['error' => 'Sorry, something went wrong. Please try again.']);
            return false;
        }

        $stationdata->store_name = $request->store_name;
        // $stationdata->opening_time = $request->opening_time;
        // $stationdata->closing_time = $request->closing_time;


        if ($request->openlocationcode != null) {

            $coredata = Importdata::where('store_location', $request->openlocationcode)->first();
            $coredata2 = Forapprovalstation::where('store_location', $request->openlocationcode)->first();

            if ($coredata == null && $coredata2 == null) {
                $stationdata->store_address = $request->store_address;
                $stationdata->store_location_latitude = $request->store_location_latitude;
                $stationdata->store_location_longitude = $request->store_location_longitude;
                $stationdata->store_location = $request->openlocationcode;
            } else {
                return response()->json(['alreadyaddederror' => 'This location has been already added.']);
            }
        }


        $gasolinet = $request->gasolinet;
        $gasolinep = $request->gasolinep;

        $cnt = count($gasolinep);
        $key = [];
        $val = [];
        for ($i = 0; $i < $cnt; $i++) {
            if ($gasolinep[$i] != null) {
                $key[] = $gasolinet[$i];
                $val[] = $gasolinep[$i];
            }
        }

        if (count($key) == 0 || count($val) == 0) {
            return response()->json(['gas_upd_error' => 'Please enter gasoline title and price']);
        }

        $gasoline = array_combine($key, $val);
        if (count($gasoline) > 0) {
            $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];
        } else {
            $gasoline = '';
            $forfil_price_gasoline = '';
        }

        $dieselt = $request->dieselt;
        $dieselp = $request->dieselp;

        $cnt2 = count($dieselp);
        $key2 = [];
        $val2 = [];
        for ($i = 0; $i < $cnt2; $i++) {
            if ($dieselp[$i] != null) {
                $key2[] = $dieselt[$i];
                $val2[] = $dieselp[$i];
            }
        }

        if (count($key2) == 0 || count($val2) == 0) {
            return response()->json(['die_upd_error' => 'Please enter diesel title and price']);
        }

        $diesel = array_combine($key2, $val2);
        if (count($diesel) > 0) {
            $forfil_price_diesel = $diesel[array_key_first($diesel)];
        } else {
            $diesel = '';
            $forfil_price_diesel = '';
        }


        $stationdata->gasoline = $gasoline;
        $stationdata->forfil_price_gasoline = $forfil_price_gasoline;
        $stationdata->diesel = $diesel;
        $stationdata->forfil_price_diesel = $forfil_price_diesel;

        $stationdata->nick_name = $request->nick_name;
        $stationdata->landmarks = $request->landmarks;
        $stationdata->comments = $request->comments;
        $stationdata->city = $request->city;

        $stationdata->save();

        return response()->json(['stationdata' => $stationdata]);
    }


    public function semisuperadmin()
    {
        $userdata = auth()->user();
        $semisuperadmins = User::where('user_type', '!=', 'admin')->get();

        return view('semisuperadmin', compact('semisuperadmins', 'userdata'));
    }

    public function removesemisuperadmin(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);

        if ($user->profile_image != null) {
            if (file_exists(public_path('images/' . $user->profile_image))) {

                @unlink(public_path('images/' . $user->profile_image));
            }
        }

        $user->tokens()->delete();
        $user->delete();

        return redirect()->back()->with('SUCCESS', 'Admin access has been removed.');
    }

    public function addsemisuperadmin(Request $request)
    {
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:15|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/',
            'phone' => 'required',
        ]);

        $data = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => 'admin',
            'user_type' => 'semi_super_admin',
        ]);

        return redirect()->back()->with('SUCCESS', 'Admin access has been created.');
    }

    public function storeimageforapp()
    {
        $userdata = auth()->user();

        $fulldatas = Storeimgforapp::with('storedata')->get();
        // dd($fulldatas);
        return view('storeimgforapp', compact('userdata', 'fulldatas'));
    }

    public function storeimgforapppageright(Request $request)
    {
        // $data = auth()->user();

        // $storedatas = Importdata::all();
        $id = $request->id;
        // dd($id);
        // $rightdatapriceapp = Pricechangereq::with('storedata')->find($id);
        $rightdatastoreimgapp = Storeimgforapp::with('storedata')->find($id);
        // dd($rightdatastoreimgapp);

        // return view('totalstation',compact('data','storedatas','rightdata'));
        $data = view('datastoreimgforapproval', compact('rightdatastoreimgapp'))->render();
        $response['data'] = $data;
        return $response;
    }

    public function rejectstoreimgreq(Request $request)
    {
        // dd($id);
        $data = Storeimgforapp::find($request->id);

        if ($data == null) {
            // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
            return false;
        } else {
            if (file_exists(public_path('images/storeimage/' . $data->storefornt_img))) {

                @unlink(public_path('images/storeimage/' . $data->storefornt_img));
            }

            $data->delete();
        }

        // return redirect()->route('priceforapproval')->with('SUCCESS','Reject request successfully');
        return true;
    }

    public function approvestoreimgreq(Request $request)
    {
        $data = Storeimgforapp::find($request->id);
        // dd($data);
        if ($data == null) {
            // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
            return false;
        } else {
            $maindata = Importdata::find($data->station_id);

            if ($maindata == null) {
                // return redirect()->route('priceforapproval')->with('ERROR','Sorry, something went wrong. Please try again.');
                return false;
            } else {

                if (file_exists(public_path('images/storeimage/' . $maindata->store_image))) {

                    @unlink(public_path('images/storeimage/' . $maindata->store_image));
                }

                $upload = Storeimage::create([
                    'store_name' => $maindata->store_name,
                    'store_image' => $data->storefornt_img,
                ]);
                $maindata->store_image = $data->storefornt_img;

                $maindata->modify_name = $data->modify_name;

                $maindata->save();

                $data->delete();
            }
        }

        // return redirect()->route('priceforapproval')->with('SUCCESS','Approve request successfully');
        return true;
    }

    public function allnotupdated()
    {
        $datas = Importdata::all();
        if ($datas != null) {
            foreach ($datas as $data) {
                $data->status = 'Not Updated';
                $data->save();
            }


            // Load libraries
            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            $range = 'Testsheet'; // Change this to the desired range.

            // Get the current values from the spreadsheet
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if ($values == null) {
                return false; // Handle the case where no data is retrieved
            }

            // Iterate through each row and update the last column
            foreach ($values as $rowIndex => $row) {
                // Extract the last value of the row
                $lastValue = end($row);

                // Modify the last value (assuming $request->status holds the new value)
                $values[$rowIndex][count($row) - 1] = 'Not Updated';
            }

            // Prepare the updated data
            $updateData = new Google_Service_Sheets_ValueRange();
            $updateData->setValues($values);

            // Update the data in the sheet
            $updateRange = $range;
            $updateOptions = ['valueInputOption' => 'RAW'];
            $service->spreadsheets_values->update($spreadsheetId, $updateRange, $updateData, $updateOptions);



        } else {
            return redirect()->back()->with('ERROR', 'Sorry, something went wrong. Please try again.');
        }

        return redirect()->route('index')->with('SUCCESS', 'All station has been marked as Not Updated');
    }

    public function appversion_submit(Request $request)
    {
        $validate = $request->validate([
            'current_version' => 'required'
        ]);

        $data = Appversion::first();
        if($data == null){
            $dt = Appversion::create([
                'current_version' => $request->current_version,
            ]);
        }else{
            $data->current_version = $request->current_version;
            $data->save();
        }

        return redirect()->route('index')->with('SUCCESS', 'App current version  has been updated!');
    }
}
