<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use App\Models\Brandlogo;
use App\Models\Importdata;
use App\Models\Storeimage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use OpenLocationCode\OpenLocationCode;
use OpenLocationCode\Tests\OpenLocationCodeTest;

// use Google\Client;
// use Google\Service\Sheets;

use Google_Client;
use Google_Service_Sheets;
use Revolution\Google\Sheets\Facades\Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_Request;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use GuzzleHttp\Client;

class DataController extends Controller
{
    public function importdata(Request $request)
    {
        // dd($request->file);
        Excel::import(new UsersImport, request()->file('file'));

        // return back();
        return response()->json(['success' => 'true', 'message' => 'File successfully uploaded on server'], 200);
    }

    public function getdata(Request $request)
    {
        $validate = $request->validate([
            'store_location' => 'required',
            'brand' => 'required|array'
        ]);

        $convert = OpenLocationCode::decode($request->store_location);
        // dd($convert);

        // $locationCode = OpenLocationCode::encode(21.1884375,72.7900625, 10);
        // dd($locationCode);

        $latitude = $convert['latitudeCenter'];
        $longitude = $convert['longitudeCenter'];

        $brand = $request->brand;
        $count = count($brand);

        for($i=0;$i<$count;$i++){
            if($brand[$i] == 'ALL'){
                $all = 'All';
            }else{
                $all = '';
            }
        }

        if($all == null)
        {
            $data = Importdata::select('id', 'brand', 'store_name', 'store_address', 'opening_time', 'closing_time', 'store_location','store_location_latitude', 'store_location_longitude', 'diesel', 'gasoline', 'otherinfo', 'brand_logo','store_image','created_at','updated_at', DB::raw(sprintf(
                '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(`store_location_latitude`)) * cos(radians(`store_location_longitude`) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(`store_location_latitude`)))) AS distance',
                $latitude,
                $longitude
            )))
                ->having('distance', '<', 15)
                ->orderBy('distance', 'asc')
                ->whereIn('brand', $brand)
                ->paginate(10);
        }else{
            $data = Importdata::select('id', 'brand', 'store_name', 'store_address', 'opening_time', 'closing_time', 'store_location','store_location_latitude', 'store_location_longitude', 'diesel', 'gasoline', 'otherinfo', 'brand_logo','store_image','created_at','updated_at', DB::raw(sprintf(
                '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(`store_location_latitude`)) * cos(radians(`store_location_longitude`) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(`store_location_latitude`)))) AS distance',
                $latitude,
                $longitude
            )))
                ->having('distance', '<', 15)
                ->orderBy('distance', 'asc')
                ->paginate(10);
        }


        if ($data != null) {
            $cnt = count($data);
            for ($i = 0; $i < $cnt; $i++) {
                if ($data[$i]->diesel != '') {

                    $dieseldata = [];
                    $dieseldatafinal = [];
                    foreach ($data[$i]->diesel as $key => $value) {
                        $dieseldata['title'] = $key;
                        $dieseldata['price'] = $value;
                        $dieseldatafinal[] = $dieseldata;
                    }

                    $data[$i]->diesel = $dieseldatafinal;
                }
            }

            for ($i = 0; $i < $cnt; $i++) {
                if ($data[$i]->gasoline != '') {

                    $gasolinedata = [];
                    $gasolinedatafinal = [];
                    foreach ($data[$i]->gasoline as $key2 => $value2) {
                        $gasolinedata['title'] = $key2;
                        $gasolinedata['price'] = $value2;
                        $gasolinedatafinal[] = $gasolinedata;
                    }

                    $data[$i]->gasoline = $gasolinedatafinal;
                }
            }
        }else{
            $data = '';
        }

        return response()->json(['success' => 'true', 'message' => 'First Click list loaded successfully', 'data' => $data], 200);
    }

    public function updatedata(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'brand' => 'required',
            'store_name' => 'required',
            'store_address' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'store_location' => 'required',
            'store_location_latitude' => 'required',
            'store_location_longitude' => 'required',
            'otherinfo' => 'required',
            'brand_logo' => 'required',
            'store_image' => 'required',
        ]);

        $id = $request->id;


        if ($id == null) {
            return response()->json(['success' => 'false', 'message' => 'Something went wrong please try again later'], 404);
        } else {
            $data = Importdata::find($id);

            if ($data == null) {
                return response()->json(['success' => 'false', 'message' => 'Something went wrong please try again later'], 404);
            }

            //update time ae location code change j nai kri shke user so ae check kravi jrur nthi

            $data->brand = strtolower($request->brand);
            $data->store_name = $request->store_name;
            $data->store_address = $request->store_address;
            $data->opening_time = $request->opening_time;
            $data->closing_time = $request->closing_time;
            $data->store_location = $request->store_location;
            $data->store_location_latitude = $request->store_location_latitude;
            $data->store_location_longitude = $request->store_location_longitude;
            $data->otherinfo = $request->otherinfo;
            $data->brand_logo = $request->brand_logo;
            $data->store_image = $request->store_image;

            if($request->diesel != null){
                $data->diesel = $request->diesel;
                $data->forfil_price_diesel = $request->diesel[array_key_first($request->diesel)];
            }else{
                $data->diesel = '';
                $data->forfil_price_diesel = '';
            }

            if($request->gasoline != null){
                $data->gasoline = $request->gasoline;
                $data->forfil_price_gasoline = $request->gasoline[array_key_first($request->gasoline)];
            }else{
                $data->gasoline = '';
                $data->forfil_price_gasoline = '';
            }

            $data->save();

            //use location code for get record bcz te change nai thto edit time ae
            // dd($data->store_location);

            //for update data
            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            //$range = 'A2:H4'; // Change this to the desired range.
            //$range = 'for Upload'; // Change this to the desired range.
            $range = 'Testsheet'; // Change this to the desired range.

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);

            $values = $response->getValues();

            if($values == null){
                return response()->json(['success' => 'false', 'message' => 'Something went wrong please try again later'], 404);
            }

            // dd($values);
            $loc = $data->store_location;

            $cnt = count($values);
            // dd($cnt);

            for($i=0;$i<$cnt;$i++)
            {
                // dd($values[$i]);
                if($values[$i]['2'] == $loc){
                    $data2 = $values[$i];
                    $rowIndex = $i;
                }
            }

            // dd($data2);
            // dd($rowIndex + 1);

            if($request->gasoline != null){
                foreach ($request->gasoline as $key1 => $value1) {
                    $result1[] = $key1 . '=' . $value1;
                }
                $gasoline = implode(',', $result1);
            }else{
                $gasoline = '';
            }


            if($request->diesel != null){
                foreach ($request->diesel as $key2 => $value2) {
                    $result2[] = $key2 . '=' . $value2;
                }
                $diesel = implode(',', $result2);
            }else{
                $diesel = '';
            }

            $values[$rowIndex] = [$request->brand,$request->store_name,$request->store_location,$request->opening_time,$request->closing_time,$gasoline,$diesel,$request->otherinfo];
            // dd($values);
            // Prepare the updated data
            $updateData = new Google_Service_Sheets_ValueRange();
            $updateData->setValues($values);

            // Update the data in the sheet
            $updateRange = $range;
            $updateOptions = ['valueInputOption' => 'RAW'];
            $service->spreadsheets_values->update($spreadsheetId, $updateRange, $updateData, $updateOptions);


            return response()->json(['success' => 'true', 'message' => 'Update data successfully'], 200);

        }
    }

    public function deletedata(Request $request)
    {
        // dd($request->id);
        $id = $request->id;

        if ($id == null) {
            return response()->json(['success' => 'false', 'message' => 'Something went wrong please try again later'], 404);
        } else {
            $data = Importdata::find($id);


            //for update data
            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            //$range = 'A2:H4'; // Change this to the desired range.
            //$range = 'for Upload'; // Change this to the desired range.
            $range = 'Testsheet'; // Change this to the desired range.

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);

            $values = $response->getValues();

            if($values == null){
                return response()->json(['success' => 'false', 'message' => 'Something went wrong please try again later'], 404);
            }

            $loc = $data->store_location;

            $ct = count($values);
            // dd($cnt);

            for($i=0;$i<$ct;$i++)
            {
                // dd($values[$i]);
                if($values[$i]['2'] == $loc){
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
            return response()->json(['success' => 'true', 'message' => 'Delete data successfully'], 200);
        }
    }

    public function userlocation(Request $request)
    {
        $validate = $request->validate([
            // 'user_location_latitude' => 'required',
            // 'user_location_longitude' => 'required',
            'type' => 'required',
            'store_location' => 'required',
            'brand' => 'required|array'
        ]);

        $convert = OpenLocationCode::decode($request->store_location);
        // dd($convert);

        // $locationCode = OpenLocationCode::encode(21.1884375,72.7900625, 10);
        // dd($locationCode);

        $latitude = $convert['latitudeCenter'];
        $longitude = $convert['longitudeCenter'];

        $brand = $request->brand;
        $count = count($brand);

        for($i=0;$i<$count;$i++){
            if($brand[$i] == 'ALL'){
                $all = 'All';
            }else{
                $all = '';
            }
        }

        if($all == null)
        {
            $type = $request->type;
            if ($type == 1) {
                $data = Importdata::select('id', 'brand', 'store_name', 'store_address', 'opening_time', 'closing_time', 'store_location_latitude', 'store_location_longitude', 'diesel', 'gasoline', 'otherinfo', 'brand_logo','store_image','created_at','updated_at', DB::raw(sprintf(
                    '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(`store_location_latitude`)) * cos(radians(`store_location_longitude`) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(`store_location_latitude`)))) AS distance',
                    $latitude,
                    $longitude
                )))
                    ->having('distance', '<', 5)
                    ->where('diesel', '!=', '')
                    ->orderBy('forfil_price_diesel', 'asc')
                    ->orderBy('distance', 'asc')
                    ->whereIn('brand', $brand)
                    ->paginate(10);
            } elseif ($type == 2) {
                $data = Importdata::select('id', 'brand', 'store_name', 'store_address', 'opening_time', 'closing_time', 'store_location_latitude', 'store_location_longitude', 'diesel', 'gasoline', 'otherinfo', 'brand_logo','store_image','created_at','updated_at', DB::raw(sprintf(
                    '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(`store_location_latitude`)) * cos(radians(`store_location_longitude`) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(`store_location_latitude`)))) AS distance',
                    $latitude,
                    $longitude
                )))
                    ->having('distance', '<', 5)
                    ->where('gasoline', '!=', '')
                    ->orderBy('forfil_price_gasoline', 'asc')
                    ->orderBy('distance', 'asc')
                    ->whereIn('brand', $brand)
                    ->paginate(10);
            } else {
                $data = '';
            }
        }else{
            $type = $request->type;
            if ($type == 1) {
                $data = Importdata::select('id', 'brand', 'store_name', 'store_address', 'opening_time', 'closing_time', 'store_location_latitude', 'store_location_longitude', 'diesel', 'gasoline', 'otherinfo', 'brand_logo','store_image','created_at','updated_at', DB::raw(sprintf(
                    '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(`store_location_latitude`)) * cos(radians(`store_location_longitude`) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(`store_location_latitude`)))) AS distance',
                    $latitude,
                    $longitude
                )))
                    ->having('distance', '<', 5)
                    ->where('diesel', '!=', '')
                    ->orderBy('forfil_price_diesel', 'asc')
                    ->orderBy('distance', 'asc')
                    ->paginate(10);
            } elseif ($type == 2) {
                $data = Importdata::select('id', 'brand', 'store_name', 'store_address', 'opening_time', 'closing_time', 'store_location_latitude', 'store_location_longitude', 'diesel', 'gasoline', 'otherinfo', 'brand_logo','store_image','created_at','updated_at', DB::raw(sprintf(
                    '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(`store_location_latitude`)) * cos(radians(`store_location_longitude`) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(`store_location_latitude`)))) AS distance',
                    $latitude,
                    $longitude
                )))
                    ->having('distance', '<', 5)
                    ->where('gasoline', '!=', '')
                    ->orderBy('forfil_price_gasoline', 'asc')
                    ->orderBy('distance', 'asc')
                    ->paginate(10);
            } else {
                $data = '';
            }
        }


        if ($data != null) {
            $cnt = count($data);
            for ($i = 0; $i < $cnt; $i++) {
                if ($data[$i]->diesel != '') {

                    $dieseldata = [];
                    $dieseldatafinal = [];
                    foreach ($data[$i]->diesel as $key => $value) {
                        $dieseldata['title'] = $key;
                        $dieseldata['price'] = $value;
                        $dieseldatafinal[] = $dieseldata;
                    }

                    $data[$i]->diesel = $dieseldatafinal;
                }
            }

            for ($i = 0; $i < $cnt; $i++) {
                if ($data[$i]->gasoline != '') {

                    $gasolinedata = [];
                    $gasolinedatafinal = [];
                    foreach ($data[$i]->gasoline as $key2 => $value2) {
                        $gasolinedata['title'] = $key2;
                        $gasolinedata['price'] = $value2;
                        $gasolinedatafinal[] = $gasolinedata;
                    }

                    $data[$i]->gasoline = $gasolinedatafinal;
                }
            }
        }

        return response()->json(['success' => 'true', 'message' => 'First Click list loaded successfully', 'data' => $data], 200);
    }


    public function importdataspreadsheet(Request $request)
    {
        //final test
        $client = new Google_Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);

        $service = new Google_Service_Sheets($client);

        $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
       // $range = 'A2:H2'; // Change this to the desired range.
        //$range = 'for Upload'; // Change this to the desired range.
        $range = 'Testsheet'; // Change this to the desired range.

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        // dd($values);

        if($values == null){
            return response()->json(['success' => 'false', 'message' => 'Something went wrong please try again later'], 404);
        }

        $cnt = count($values);

        for($i=1;$i<$cnt;$i++){
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
                    return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
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

                    for($a=0;$a<$cnt2;$a++)
                    {
                        if($datalocation->results[$a]->types == 'street_address')
                        {
                            $store_address = $datalocation->results[$a]->formatted_address;
                        }else{
                            $store_address = $datalocation->results[0]->formatted_address;
                        }
                    }
                } else {
                    return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
                }

                //diesel and gasoline data save code
                if ($values[$i][6] != null) {
                    $diesel = [];
                    $values[$i][6] = rtrim($values[$i][6], ',');
                    foreach (explode(',', $values[$i][6]) as $item) {
                        $parts = explode('=', $item);
                        $diesel[trim($parts[0])] = $parts[1];
                    }

                    //remove null
                    foreach($diesel as $key1=>$value1)
                    {
                        if(is_null($value1) || $value1 == '')
                            unset($diesel[$key1]);
                    }

                    //for filter set first in other field
                    $forfil_price_diesel = $diesel[array_key_first($diesel)];
                } else {
                    $diesel = '';
                    $forfil_price_diesel = '';
                }

                if ($values[$i][5] != null) {
                    $gasoline = [];
                    $values[$i][5] = rtrim($values[$i][5], ',');
                    foreach (explode(',', $values[$i][5]) as $item2) {
                        $parts2 = explode('=', $item2);
                        $gasoline[trim($parts2[0])] = $parts2[1];
                    }

                    //remove null
                    foreach($gasoline as $key2=>$value2)
                    {
                        if(is_null($value2) || $value2 == '')
                            unset($gasoline[$key2]);
                    }

                    //for filter set first in other field
                    $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];
                } else {
                    $gasoline = '';
                    $forfil_price_gasoline = '';
                }



                //updated for brand logo after api and table
                $branddetail = Brandlogo::where('brand',strtolower($values[$i][0]))->first();

                if($branddetail != null){
                    $brand_logo = $branddetail->brand_logo;
                }else{
                    $brand_logo = '';
                }

                //for store image
                $storedetail = Storeimage::where('store_name',$values[$i][1])->first();

                if($storedetail != null){
                    $store_image = $storedetail->store_image;
                }else{
                    $store_image = '';
                }

                // dump($values[$i][1]);
                $data = Importdata::create([
                    'user_id' => $user->id,
                    'brand' => strtolower($values[$i][0]),
                    'store_name' => $values[$i][1],
                    'store_address' => $store_address,
                    'opening_time' => $values[$i][3],
                    'closing_time' => $values[$i][4],
                    'store_location' => $values[$i][2],
                    'store_location_latitude' => $latitude,
                    'store_location_longitude' => $longitude,
                    'diesel' => $diesel,
                    'gasoline' => $gasoline,
                    'otherinfo' => $values[$i][7],
                    'brand_logo' => $brand_logo,
                    'store_image' => $store_image,
                    'forfil_price_diesel' => $forfil_price_diesel,
                    'forfil_price_gasoline' => $forfil_price_gasoline,
                ]);

            }else{
                // koi field ni value ma changes hshe ae krva
                // aevu lage ae check kravya baki na direct
                // store location ma koi changes nai thai

                if($coredata->brand != $values[$i][0]){
                    $coredata->brand = strtolower($values[$i][0]);

                }

                //updated for brand logo after api and table
                $branddetail = Brandlogo::where('brand',strtolower($values[$i][0]))->first();

                if($branddetail != null){
                    $brand_logo = $branddetail->brand_logo;
                }else{
                    $brand_logo = '';
                }

                //for store image
                $storedetail = Storeimage::where('store_name',$values[$i][1])->first();

                if($storedetail != null){
                    $store_image = $storedetail->store_image;
                }else{
                    $store_image = '';
                }

                $coredata->brand_logo = $brand_logo;

                $coredata->store_image = $store_image;

                $coredata->store_name = $values[$i][1];

                $coredata->opening_time =$values[$i][3];
                $coredata->closing_time = $values[$i][4];


                //diesel and gasoline
                if ($values[$i][6] != null) {
                    $diesel = [];
                    $values[$i][6] = rtrim($values[$i][6], ',');
                    foreach (explode(',', $values[$i][6]) as $item) {
                        $parts = explode('=', $item);
                        $diesel[trim($parts[0])] = $parts[1];
                    }

                    //remove null
                    foreach($diesel as $key1=>$value1)
                    {
                        if(is_null($value1) || $value1 == '')
                            unset($diesel[$key1]);
                    }

                    //for filter set first in other field
                    $forfil_price_diesel = $diesel[array_key_first($diesel)];
                } else {
                    $diesel = '';
                    $forfil_price_diesel = '';
                }

                if ($values[$i][5] != null) {
                    $gasoline = [];
                    $values[$i][5] = rtrim($values[$i][5], ',');
                    foreach (explode(',', $values[$i][5]) as $item2) {
                        $parts2 = explode('=', $item2);
                        $gasoline[trim($parts2[0])] = $parts2[1];
                    }

                    //remove null
                    foreach($gasoline as $key2=>$value2)
                    {
                        if(is_null($value2) || $value2 == '')
                            unset($gasoline[$key2]);
                    }

                    //for filter set first in other field
                    $forfil_price_gasoline = $gasoline[array_key_first($gasoline)];

                } else {
                    $gasoline = '';
                    $forfil_price_gasoline = '';
                }

                $coredata->diesel = $diesel;
                $coredata->gasoline = $gasoline;


                $coredata->forfil_price_diesel = $forfil_price_diesel;
                $coredata->forfil_price_gasoline = $forfil_price_gasoline;

                $coredata->otherinfo = $values[$i][7];

                $coredata->save();
            }
        }


        //deleted record of spreadsheet remove in database code
        $totaldb = Importdata::all();

        foreach($totaldb as $totdb)
        {
            $locationExists = false;
            // dd($totdb);
            for($i=1;$i<$cnt;$i++){
                if($values[$i][2] === $totdb->store_location){
                    $locationExists = true;
                    break;
                }
            }

            if ($locationExists) {

            } else {
                $totdb->delete();
            }

        }

        return response()->json(['success' => 'true', 'message' => 'Data synced with google sheet successfully'], 200);

    }

    public function adddatamanually(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'brand' => 'required',
            'store_name' => 'required',
            'store_location' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'diesel' => 'required',
            'gasoline' => 'required',
            'otherinfo' => 'required',
        ]);


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
                return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
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

                for($a=0;$a<$cnt2;$a++)
                {
                    if($datalocation->results[$a]->types == 'street_address')
                    {
                        $store_address = $datalocation->results[$a]->formatted_address;
                    }else{
                        $store_address = $datalocation->results[0]->formatted_address;
                    }
                }

            } else {
                return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
            }

            //diesel and gasoline data save code
            if($request->diesel != null){
                $diesel = $request->diesel;
                $forfil_price_diesel = $request->diesel[array_key_first($request->diesel)];
            }else{
                $diesel = '';
                $forfil_price_diesel = '';
            }

            if($request->gasoline != null){
                $gasoline = $request->gasoline;
                $forfil_price_gasoline = $request->gasoline[array_key_first($request->gasoline)];
            }else{
                $gasoline = '';
                $forfil_price_gasoline = '';
            }



            //updated for brand logo after api and table
            $branddetail = Brandlogo::where('brand',strtolower($request->brand))->first();

            if($branddetail != null){
                $brand_logo = $branddetail->brand_logo;
            }else{
                $brand_logo = '';
            }

            //for store image
            $storedetail = Storeimage::where('store_name',$request->store_name)->first();

            if($storedetail != null){
                $store_image = $storedetail->store_image;
            }else{
                $store_image = '';
            }

            $data = Importdata::create([
                'user_id' => $user->id,
                'brand' => strtolower($request->brand),
                'store_name' => $request->store_name,
                'store_address' => $store_address,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'store_location' => $request->store_location,
                'store_location_latitude' => $latitude,
                'store_location_longitude' => $longitude,
                'diesel' => $diesel,
                'gasoline' => $gasoline,
                'otherinfo' => $request->otherinfo,
                'brand_logo' => $brand_logo,
                'store_image' => $store_image,
                'forfil_price_diesel' => $forfil_price_diesel,
                'forfil_price_gasoline' => $forfil_price_gasoline,
            ]);


            $client = new Google_Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(Google_Service_Sheets::SPREADSHEETS);

            $service = new Google_Service_Sheets($client);

            $spreadsheetId = '1Q8X7Vse-MGZ6fbGfv_BsPBML2j7l6_WieZsm413V8R0';
            $range = 'Testsheet'; // Change this to the desired sheet name.

            if($request->gasoline != null){
                foreach ($request->gasoline as $key1 => $value1) {
                    $result1[] = $key1 . '=' . $value1;
                }
                $gasoline = implode(',', $result1);
            }else{
                $gasoline = '';
            }


            if($request->diesel != null){
                foreach ($request->diesel as $key2 => $value2) {
                    $result2[] = $key2 . '=' . $value2;
                }
                $diesel = implode(',', $result2);
            }else{
                $diesel = '';
            }

            $values[] = [$request->brand,$request->store_name,$request->store_location,$request->opening_time,$request->closing_time,$gasoline,$diesel,$request->otherinfo]; // Assuming you're sending data in the request.

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

            $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);



        }else{
            return response()->json(['success' => 'false', 'message' => 'This location has been already added.'], 404);
        }


        return response()->json(['success' => 'true', 'message' => 'Data added successfully'], 200);
    }


    public function addbrandlogo(Request $request)
    {
        // dd('okay');
        $request->validate([
            'brand' => 'required',
            'brand_logo' => 'required',
        ]);

        $brandtable = Brandlogo::where('brand',$request->brand)->first();

        if($brandtable == null){
            if($request->hasFile('brand_logo')){

                $img = 'IMG'.'.'.rand('1111','9999').time().'.'.'png';

                //for test
                // $destinationPath = 'https://firstclick-logo.brijeshnavadiya.com/';
                //$request->brand_logo->move($destinationPath,$img);

                //$img = $destinationPath.$img;

                //for curerent
                $request->brand_logo->move('images/logo/',$img);

                $img = url('images/logo/'.$img);

            }else{
                return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
            }

            $data = Brandlogo::create([
                'brand' => strtolower($request->brand),
                'brand_logo' => $img,
            ]);

            return response()->json(['success' => 'true', 'message' => 'Brand logo added successfully','data'=>$data], 200);
        }else{
            return response()->json(['success' => 'false', 'message' => 'This brand logo has been already added.'], 404);
        }
    }

    public function getbrandlogo()
    {
        $data = Brandlogo::all();

        return response()->json(['success' => 'true', 'message' => 'Brand logo get successfully','data'=>$data], 200);
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

    public function addstoreimage(Request $request)
    {
        // dd('okay');

        $validate = $request->validate([
            'store_name' => 'required',
            'store_image' => 'required',
        ]);

        $storeimagetable = Storeimage::where('store_name',$request->store_name)->first();

        if($storeimagetable == null){
            if($request->hasFile('store_image')){

                $img = 'IMG'.'.'.rand('1111','9999').time().'.'.'png';

                //for test
                // $destinationPath = 'https://firstclick-logo.brijeshnavadiya.com/';
                //$request->store_image->move($destinationPath,$img);

                //$img = $destinationPath.$img;

                //for curerent
                $request->store_image->move(public_path('images/storeimage/'),$img);

                // $img = url('images/storeimage/'.$img);

            }else{
                return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
            }

            $data = Storeimage::create([
                'store_name' => $request->store_name,
                'store_image' => $img,
            ]);

            return response()->json(['success' => 'true', 'message' => 'Store image added successfully','data'=>$data], 200);
        }else{
            return response()->json(['success' => 'false', 'message' => 'This store image has been already added.'], 404);
        }
    }

    public function getstoreimage()
    {
        $data = Storeimage::all();

        return response()->json(['success' => 'true', 'message' => 'Store image get successfully','data'=>$data], 200);
    }

    public function updatestoreimage(Request $request)
    {
        // dd('okay');
        $validate = $request->validate([
            'store_name' => 'required',
            'store_image' => 'required',
        ]);

        $storeimagetable = Storeimage::where('store_name',$request->store_name)->first();


        if($storeimagetable != null){
            if (file_exists(public_path('images/storeimage/'.$storeimagetable->store_image))) {

                @unlink(public_path('images/storeimage/'.$storeimagetable->store_image));

            }

            $storeimagetable->delete();

            if($request->hasFile('store_image')){

                $img = 'IMG'.'.'.rand('1111','9999').time().'.'.'png';

                //for test
                // $destinationPath = 'https://firstclick-logo.brijeshnavadiya.com/';
                //$request->store_image->move($destinationPath,$img);

                //$img = $destinationPath.$img;

                //for curerent
                $request->store_image->move(public_path('images/storeimage/'),$img);

                // $img = url('images/storeimage/'.$img);
            }

            $data = Storeimage::create([
                'store_name' => $request->store_name,
                'store_image' => $img,
            ]);

            return response()->json(['success' => 'true', 'message' => 'Store image updated successfully','data'=>$data], 200);

        }else{
            return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
        }



    }

}
