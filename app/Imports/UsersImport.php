<?php

namespace App\Imports;

use App\Models\Brandlogo;
use App\Models\Importdata;
use App\Models\Storeimage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use OpenLocationCode\OpenLocationCode;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class UsersImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 2;
    }


    public function model(array $row)
    {
        $user = auth()->user();
        // dd($row);

        //pluscode convert into lat long
        // $data = OpenLocationCode::decode($row[4]);

        // dd($data);
        // $latitude = $data['latitudeCenter'];
        // $longitude = $data['longitudeCenter'];



        //check already same pluscode address exist or not
        $coredata = Importdata::where('store_location', $row[2])->first();

        if ($coredata == null) {
            // location code convert into lat lon
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $client = new Client();
            $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json", [
                'query' => [
                    'address' => $row[2],
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
                $store_address = null;

                for ($a = 0; $a < $cnt2; $a++) {
                    if (in_array('street_address',$datalocation->results[$a]->types) ) {
                        $store_address = $datalocation->results[$a]->formatted_address;
                    }
                }
                if($store_address == null){
                    $store_address = $datalocation->results[0]->formatted_address;
                }
            } else {
                return response()->json(['success' => 'false', 'message' => 'Something Went Wrong'], 404);
            }

            //diesel and gasoline data save code
            if ($row[6] != null) {
                $diesel = [];
                $row[6] = rtrim($row[6], ',');
                foreach (explode(',', $row[6]) as $item) {
                    $parts = explode('=', $item);
                    $diesel[trim($parts[0])] = $parts[1];
                }

                //remove null
                foreach($diesel as $key1=>$value1)
                {
                    if(is_null($value1) || $value1 == '')
                    {
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

            if ($row[5] != null) {
                $gasoline = [];
                $row[5] = rtrim($row[5], ',');
                foreach (explode(',', $row[5]) as $item2) {
                    $parts2 = explode('=', $item2);
                    $gasoline[trim($parts2[0])] = $parts2[1];
                }

                //remove null
                foreach($gasoline as $key2=>$value2)
                {
                    if(is_null($value2) || $value2 == ''){
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
            $branddetail = Brandlogo::where('brand',strtolower($row[0]))->first();

            if($branddetail != null){
                $brand_logo = $branddetail->brand_logo;
            }else{
                $brand_logo = '';
            }

            //for store image
            $storedetail = Storeimage::where('store_name',$row[1])->first();

            if($storedetail != null){
                $store_image = $storedetail->store_image;
            }else{
                $store_image = '';
            }

            return new Importdata([
                'user_id' => $user->id,
                'brand' => strtolower($row[0]),
                'store_name' => $row[1],
                'store_address' => $store_address,
                'opening_time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('H:i'),
                'closing_time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4])->format('H:i'),
                'store_location' => $row[2],
                'store_location_latitude' => $latitude,
                'store_location_longitude' => $longitude,
                'diesel' => $diesel,
                'gasoline' => $gasoline,
                'otherinfo' => isset($row[7]) ? $row[7] : null,
                'brand_logo' => $brand_logo,
                'store_image' => $store_image,
                'forfil_price_diesel' => $forfil_price_diesel,
                'forfil_price_gasoline' => $forfil_price_gasoline,
            ]);
        }else{
            // koi field ni value ma changes hshe ae krva
            // aevu lage ae check kravya baki na direct
            // store location ma koi changes nai thai

            if($coredata->brand != $row[0]){
                $coredata->brand = strtolower($row[0]);
            }

            //updated for brand logo after api and table
            $branddetail = Brandlogo::where('brand',strtolower($row[0]))->first();

            if($branddetail != null){
                $brand_logo = $branddetail->brand_logo;
            }else{
                $brand_logo = '';
            }

            //for store image
            $storedetail = Storeimage::where('store_name',$row[1])->first();

            if($storedetail != null){
                $store_image = $storedetail->store_image;
            }else{
                $store_image = '';
            }

            $coredata->brand_logo = $brand_logo;

            $coredata->store_image = $store_image;

            $coredata->store_name = $row[1];

            $coredata->opening_time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('H:i');
            $coredata->closing_time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4])->format('H:i');


            //diesel and gasoline
            if ($row[6] != null) {
                $diesel = [];
                $row[6] = rtrim($row[6], ',');
                foreach (explode(',', $row[6]) as $item) {
                    $parts = explode('=', $item);
                    $diesel[trim($parts[0])] = $parts[1];
                }

                //remove null
                foreach($diesel as $key1=>$value1)
                {
                    if(is_null($value1) || $value1 == '')
                    {
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

            if ($row[5] != null) {
                $gasoline = [];
                $row[5] = rtrim($row[5], ',');
                foreach (explode(',', $row[5]) as $item2) {
                    $parts2 = explode('=', $item2);
                    $gasoline[trim($parts2[0])] = $parts2[1];
                }

                //remove null
                foreach($gasoline as $key2=>$value2)
                {
                    if(is_null($value2) || $value2 == '')
                    {
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

            $coredata->diesel = $diesel;
            $coredata->gasoline = $gasoline;


            $coredata->forfil_price_diesel = $forfil_price_diesel;
            $coredata->forfil_price_gasoline = $forfil_price_gasoline;

            $coredata->otherinfo = isset($row[7]) ? $row[7] : null;

            $coredata->save();
        }


    }
}
