<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Station</title>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/totalstation.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script> --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

    <style>
        #map1 {
            height: 400px;
            width: 100%;
        }

        .controls {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
        }
    </style>
</head>

<body>

    @include('header')

    <div class="d-flex justify-content-center alert-box">
        @error('brand')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('store_name')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('store_location')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('store_address')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        {{-- @error('opening_time')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('closing_time')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror --}}
        @error('landmarks')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @if (session()->has('SUCCESS'))
            <span class="successtx my-2">{{ session()->get('SUCCESS') }}</span>
        @endif
        @if (session()->has('ERROR'))
            <span class="errortx my-2">{{ session()->get('ERROR') }}</span>
        @endif
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 p-0">
                <div class="back">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="headingleft">Fuel Stations</span>
                        </div>
                        <div style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#addnewstation">
                            <img src="{{ asset('assets/images/add-circle.png') }}" alt="add-circle">
                        </div>
                    </div>

                    {{-- <div class="mt-3">
                        <a href="{{ route('allnotupdated') }}" onclick="Loader()">
                            <div class="notupdbutt">
                                <span class="notupdbutttx">Mark All as Not Updated</span>
                            </div>
                        </a>
                    </div> --}}

                    <div class="mt-3">
                        <input type="text" name="search" id="search" placeholder="Search Station"
                            class="intfield d-none">
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <div class="menubox1 widthclass">
                                {{-- <span class="menutx">Active (23)</span> --}}
                                <span class="menutx">All&nbsp({{ $allcount }})</span>
                            </div>
                            <div class="menubox2 widthclass">
                                {{-- <span class="menutx">Archived (20)</span> --}}
                                <span class="menutx">For Approval&nbsp({{ $approvecount }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- content 1 --}}
                    <div class="content1">
                        <div class="display-data2">

                        </div>




                    </div>

                    {{-- content 2 --}}
                    <div class="content2">
                        @foreach ($storedatasforapps as $storedatasforapp)
                            <div class="row mt-2 brandbox" data-id="{{ $storedatasforapp->id }}" onclick="boxforneedapprove(this)">
                                <div class="col-md-2 p-0 d-flex justify-content-center align-items-center">
                                    <div class="brand_logo">
                                        @if ($storedatasforapp->brand_logo == null)
                                            <img src="{{ asset('assets/images/brandlogostatic.svg') }}"
                                                alt="brand_logo" class="brand_logoimg">
                                        @else
                                            <img src="{{ asset('/public/images/brandlogo/' . $storedatasforapp->brand_logo) }}"
                                                alt="brand_logo" class="brand_logoimg">
                                            {{-- <img src="{{ asset('images/brandlogo/' . $storedatasforapp->brand_logo) }}" alt="brand_logo"
                                            class="brand_logoimg"> --}}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-10 p-0">
                                    <div>
                                        <span class="brandhead">{{ $storedatasforapp->store_name }}</span>
                                    </div>
                                    {{-- <div class="d-flex">
                                        <img src="{{ asset('assets/images/location.svg') }}" alt="">
                                        <span class="brandtx">{{ $storedatasforapp->store_address }}</span>
                                    </div> --}}
                                    <div class="d-flex">
                                        <img src="{{ asset('assets/images/location.svg') }}" alt="" style="margin-right: 4px">
                                        <span class="brandtx">{{ $storedatasforapp->landmarks ? $storedatasforapp->landmarks : 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="brandtxlastupd updatedbytxforapp{{ $storedatasforapp->id }}"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="col-md-9 backright" id="screen1">
                <div class="display-data">

                </div>

            </div>


        </div>
    </div>

    <div class="modal fade" id="addnewstation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Add New Fuel Station</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <form action="{{ route('addnewstation') }}" method="POST" enctype="multipart/form-data"> --}}
                    <form id="formdataaddmaunally" enctype="multipart/form-data">
                        @csrf
                        {{-- <span id="addmanerror" class="errortx my-2"></span> --}}
                        <div class="d-flex justify-content-center d-none" id="brlogobox">
                            <div class="brand_logo">
                                <img src="" alt="brand_logo" id="brlogo" class="brand_logoimg">
                            </div>
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Brand</span>
                            <select class="form-select" name="brand" id="selectoption">
                                <option selected disabled>Select Brand</option>
                                {{-- <option value="shell">Shell</option>
                                <option value="petron">Petron</option>
                                <option value="caltex">Caltex</option>
                                <option value="phoenix">Phoenix</option>
                                <option value="seaoil">Seaoil</option>
                                <option value="petro gazz">Petro gazz</option>
                                <option value="ptt">Ptt</option>
                                <option value="unioil">Unioil</option>
                                <option value="jetti">Jetti</option>
                                <option value="rephil">Rephil</option>
                                <option value="clean fuel">Clean fuel</option>
                                <option value="flying v">Flying v</option>
                                <option value="ecooil">Ecooil</option>
                                <option value="total">Total</option> --}}
                                @foreach ($brand_datas as $brand_data)
                                    <option value="{{ $brand_data->brand }}">{{ $brand_data->brand }}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>
                            <span id="brand_error" class="errortx errornull"></span>
                        </div>
                        <div class="mb-3" id="brandtxfield">
                            <span class="fldname">Brand Name</span>
                            <input type="text" class="fld" name="brandother" placeholder="Enter Brand Name">
                            <span id="otherbrand_error" class="errortx errornull"></span>
                            <span id="otherbrandalreadyadded" class="errortx errornull"></span>
                        </div>
                        <div class="mb-3 d-none" id="brandimgfield">
                            <span class="fldname">Brand Logo</span>
                            <input type="file" class="fld" id="file-input" name="brandotherlogo">
                            <span id="otherbrandlogo_error" class="errortx errornull"></span>
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Store Name</span>
                            <input type="text" class="fld" name="store_name" placeholder="Enter Store Name">
                            <span id="store_name_error" class="errortx errornull"></span>
                        </div>
                        <div class="mb-3 position-relative">
                            <span class="fldname">Location Code</span>
                            <input type="text" class="fld" id="openlocationcodeadd" name="store_location"
                                placeholder="Select From Map">
                            <img src="{{ asset('assets/images/gps.svg') }}" alt="gps" class="gpsicon"
                                data-bs-toggle="modal" data-bs-target="#gpsmodal">
                                <span id="store_location_error" class="errortx errornull"></span>
                        </div>
                        <div class="mb-3">
                            <span class="fldname">City/Province</span>
                            <input type="text" class="fld" name="city" placeholder="Enter City Name">
                            <span id="city_error" class="errortx errornull"></span>
                        </div>

                        <div class="mb-3">
                            <span class="fldname">Landmarks</span>
                            <input type="text" class="fld" name="landmarks" placeholder="Landmarks">
                            <span id="otherinfo_error" class="errortx errornull"></span>
                        </div>

                        {{-- <div class="mb-3">
                            <span class="fldname">Opening Time</span>
                            <input type="text" class="fld" name="opening_time" placeholder="00:00">
                            <span id="opening_time_error" class="errortx errornull"></span>
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Closing Time</span>
                            <input type="text" class="fld" name="closing_time" placeholder="23:59">
                            <span id="closing_time_error" class="errortx errornull"></span>
                        </div> --}}

                        <div class="mb-3">

                            <div class="d-none" id="shellbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Fuel Save"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="V-Power"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="V-Power Racing"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Fuel Save"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="V-Power Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="petronbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Xtra Advance"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="XCS"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Blaze 100"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel Max"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Turbo Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="caltexbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Silver"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Platinum"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Power Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="phoenixbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Super Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium 95"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium 98"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="seaoilbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Extreme U"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Extreme 95"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Extreme 97"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="petrogazzbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="pttbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="BlueGasoline 93+"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="BlueGasoline 95+"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="BlueGasoline 97+"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="unioilbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Gasoline 91"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Gasoline 95"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Gasoline 97"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="jettibox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="AccelRate"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="JX Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="rephilbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="cleanfuelbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="flyingvbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="DeciVel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="ecooilbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="totalbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="otherbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Gasoline Price</span>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Regular"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="inputFormRow">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_tit[]" id="field-name" value="Premium"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="gas_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow"></div>
                                        <div id="addRow" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="fldname">Diesel Price</span>
                                        </div>
                                        <div id="inputFormRow2">
                                            <div class="input-group mb-3 row">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_tit[]" id="field-name" value="Diesel"
                                                        placeholder="Title">
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" class="fld"
                                                        name="die_pri[]" id="field-value" placeholder="Price">
                                                </div>
                                                <div class="input-group-append col-md-2">
                                                    <img src="{{ asset('assets/images/minusicon.svg') }}"
                                                        alt="remove_icon" class="cusicon" id="removeRow2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newRow2"></div>
                                        <div id="addRow2" style="cursor: pointer;" class="d-flex">
                                            <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon"
                                                class="cusicon2">
                                            <span class="addmoretx">Add more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <span id="gasoline_error" class="errortx errornull"></span>
                                </div>
                                <div class="col-md-6">
                                    <span id="diesel_error" class="errortx errornull"></span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="mb-3">
                            <span class="fldname">Select Store Image</span>
                            <input type="file" class="fld" name="store_image">
                            <span id="storeimage_error" class="errortx errornull"></span>
                        </div> --}}

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbutt" data-bs-dismiss="modal">
                                <span class="canceltx">Cancel</span>
                            </div>
                            <button class="invcontbutt" type="submit">
                                <span class="invconttx">Confirm</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="gpsmodal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Select Location</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="click-map" style="height: 400px;"></div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button class="invcontbutt" data-bs-dismiss="modal">
                            <span class="invconttx">Select</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            $('.nav-tabs a').on('shown.bs.tab', function() {
                var tabIndicator = $('.tab-slider');
                tabIndicator.width($(this).width());
                tabIndicator.css('transform', 'translateX(' + $(this).position().left + 'px)');
            });
        });
    </script> --}}

    <script>
        $(function(){
            $('#file-input').change(function(){
                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
                {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                    $('#brlogo').attr('src', e.target.result);
                    }
                reader.readAsDataURL(input.files[0]);
                }
                else
                {
                $('#brlogo').attr('src', '');
                }
            });

        });
    </script>

    <script>
        var search = '';
        $(document).ready(function() {

            load_data();

            function load_data() {
                // alert(search);
                $.ajax({
                    url: "{{ route('totalstation') }}",
                    method: 'GET',
                    data: {
                        search: search,
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        // alert();
                        $('.display-data2').html(data.data);
                    }
                })
            }

            //Search name
            $(document).on('keyup', '#search', function() {
                search = $(this).val(); // not put "var" before this variable...
                load_data();
            });

        });
    </script>


    <script>
        // Add event listeners to handle the dynamic behavior
        document.addEventListener("DOMContentLoaded", function() {
            var dropdown = document.querySelector(".dropdown");

            // Toggle the active class on click
            dropdown.addEventListener("click", function() {
                dropdown.classList.toggle("active");
            });

            // Hide the dropdown if the user clicks outside of it
            window.addEventListener("click", function(event) {
                if (!event.target.closest(".dropdown")) {
                    dropdown.classList.remove("active");
                }
            });
        });
    </script>

    <script>
        $('.menubox2').on('click', function(e) {
        e.preventDefault();
        $(this).addClass('bordercusclass');
        $('.menubox1').removeClass('bordercusclass');
        $('.content2').addClass('activeclass');
        $('.content1').removeClass('activeclass');
        $('#search').removeClass('d-block');
        $('#search').addClass('d-none');

        var id = {{ isset($storedatasforapps['0']) ? $storedatasforapps['0']->id : 0 }};
        // alert(id);
        console.log(id);

        if (localStorage.getItem("afterupdateidforupp") != null) {
            id = localStorage.getItem("afterupdateidforupp");
        }

        Loader()
        $.ajax({
            type: "GET",
            url: "{{ route('storepagerightforapp') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
            },
            dataType: "json",
            success: function(data) {
                Loaderclose();
                $('.display-data').html(data.data);
            }
        });
    });
    </script>

    <script>

        function brandbox(data) {
            var id = $(data).attr('data-id');
            // alert(id);
            console.log(id);
            $.ajax({
                type: "GET",
                url: "{{ route('storepageright') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                success: function(data) {
                    $('.display-data').html(data.data);
                }
            });
        }

        function boxforneedapprove(data) {
            var id = $(data).attr('data-id');
            // alert(id);
            console.log(id);
            $.ajax({
                type: "GET",
                url: "{{ route('storepagerightforapp') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                success: function(data) {
                    $('.display-data').html(data.data);
                }
            });
        }



    </script>
    <script>
        setTimeout(function() {
            $('.alert-box').remove();
        }, 3000);
    </script>
    <script>
        $('#selectoption').change(function() {
            var option = $(this).val();
            console.log(option);

            $('#brandtxfield').addClass('d-none');
            $('#brandtxfield').removeClass('d-block');
            $('#brandimgfield').addClass('d-none');
            $('#brandimgfield').removeClass('d-block');

            //img null every time change
            $('#brlogo').attr('src', '');

            if (option == 'other') {
                $('#brandtxfield').removeClass('d-none');
                $('#brandtxfield').addClass('d-block');
                $('#brandimgfield').removeClass('d-none');
                $('#brandimgfield').addClass('d-block');
            }

            $('#shellbox').removeClass('d-block');
            $('#shellbox').addClass('d-none');
            $('#petronbox').removeClass('d-block');
            $('#petronbox').addClass('d-none');
            $('#caltexbox').removeClass('d-block');
            $('#caltexbox').addClass('d-none');
            $('#phoenixbox').removeClass('d-block');
            $('#phoenixbox').addClass('d-none');
            $('#seaoilbox').removeClass('d-block');
            $('#seaoilbox').addClass('d-none');
            $('#petrogazzbox').removeClass('d-block');
            $('#petrogazzbox').addClass('d-none');
            $('#pttbox').removeClass('d-block');
            $('#pttbox').addClass('d-none');
            $('#unioilbox').removeClass('d-block');
            $('#unioilbox').addClass('d-none');
            $('#jettibox').removeClass('d-block');
            $('#jettibox').addClass('d-none');
            $('#rephilbox').removeClass('d-block');
            $('#rephilbox').addClass('d-none');
            $('#cleanfuelbox').removeClass('d-block');
            $('#cleanfuelbox').addClass('d-none');
            $('#flyingvbox').removeClass('d-block');
            $('#flyingvbox').addClass('d-none');
            $('#ecooilbox').removeClass('d-block');
            $('#ecooilbox').addClass('d-none');
            $('#totalbox').removeClass('d-block');
            $('#totalbox').addClass('d-none');
            $('#otherbox').removeClass('d-block');
            $('#otherbox').addClass('d-none');


            //for img
            // var base_url_for_img = 'http://127.0.0.1:8000/images/brandlogo/';
            var base_url_for_img = 'https://firstclick-v1.brijeshnavadiya.com/public/images/brandlogo/';
            // var base_url_for_img = 'https://admin.firstclick.ph/public/images/brandlogo/';

            if (option == 'shell') {
                $('#shellbox').removeClass('d-none');
                $('#shellbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'shell',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'petron') {
                $('#petronbox').removeClass('d-none');
                $('#petronbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'petron',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'caltex') {
                $('#caltexbox').removeClass('d-none');
                $('#caltexbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'caltex',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'phoenix') {
                $('#phoenixbox').removeClass('d-none');
                $('#phoenixbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'phoenix',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'seaoil') {
                $('#seaoilbox').removeClass('d-none');
                $('#seaoilbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'seaoil',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'petro gazz') {
                $('#petrogazzbox').removeClass('d-none');
                $('#petrogazzbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'petro gazz',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'ptt') {
                $('#pttbox').removeClass('d-none');
                $('#pttbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'ptt',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'unioil') {
                $('#unioilbox').removeClass('d-none');
                $('#unioilbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'unioil',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'jetti') {
                $('#jettibox').removeClass('d-none');
                $('#jettibox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'jetti',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'rephil') {
                $('#rephilbox').removeClass('d-none');
                $('#rephilbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'rephil',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'clean fuel') {
                $('#cleanfuelbox').removeClass('d-none');
                $('#cleanfuelbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'clean fuel',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'flying v') {
                $('#flyingvbox').removeClass('d-none');
                $('#flyingvbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'flying v',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'ecooil') {
                $('#ecooilbox').removeClass('d-none');
                $('#ecooilbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'ecooil',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'total') {
                $('#totalbox').removeClass('d-none');
                $('#totalbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: 'total',
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
            else if (option == 'other') {
                $('#otherbox').removeClass('d-none');
                $('#otherbox').addClass('d-block');
                // $('#brlogobox').removeClass('d-block').addClass('d-none');
                // $('#brlogo').attr('src', '');
                $('#brlogobox').removeClass('d-none').addClass('d-block');
                $('#brlogo').attr('src', base_url_for_img + 'logo.png');
            }
            else{
                $('#otherbox').removeClass('d-none');
                $('#otherbox').addClass('d-block');
                $.ajax({
                    type: "GET",
                    url: "{{ route('getbrandlogopor') }}",
                    data: {
                        brand: option,
                    },
                    success: function(result) {
                        // alert(result.brand_logo);
                        $('#brlogobox').removeClass('d-none').addClass('d-block');
                        $('#brlogo').attr('src', base_url_for_img + result.brand_logo);
                    }
                });
            }
        });
    </script>
    <script>
        // Initialize the click map
        var clickMap = new google.maps.Map(document.getElementById('click-map'), {
            center: {
                lat: 0,
                lng: 0
            }, // Set your default center
            zoom: 15,
        });

        // Get and display the current location on the click map
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var currentLat = position.coords.latitude;
                var currentLng = position.coords.longitude;

                // Display the current location
                console.log('Current Location:', currentLat, currentLng);

                // Set the current location as the center of the click map
                clickMap.setCenter(new google.maps.LatLng(currentLat, currentLng));

                // Add a marker for the current location
                new google.maps.Marker({
                    position: {
                        lat: currentLat,
                        lng: currentLng
                    },
                    map: clickMap,
                    title: 'Current Location'
                });
            }, function(error) {
                console.error('Error getting current location:', error);
                setStaticLocation();
            });
        } else {
            console.error('Geolocation is not supported by this browser.');
            setStaticLocation();
        }

        var marker;
        // Add click event listener to get coordinates of clicked location
        clickMap.addListener('click', function(event) {
            var clickedLat = event.latLng.lat();
            var clickedLng = event.latLng.lng();

            // Display the clicked coordinates
            console.log('Clicked Coordinates:', clickedLat, clickedLng);

            // Clear the previous marker
            if (marker) {
                marker.setMap(null);
            }

            // Set the clicked coordinates as the center of the click map
            clickMap.setCenter(new google.maps.LatLng(clickedLat, clickedLng));


            // Add a marker for the clicked location
            marker = new google.maps.Marker({
                position: {
                    lat: clickedLat,
                    lng: clickedLng
                },
                map: clickMap,
                title: 'Clicked Location'
            });

            // You can add additional logic here as needed
            $.ajax({
                type: "GET",
                url: "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + clickedLat + "," +
                    clickedLng + "&key=AIzaSyAv8cnP0w7bCJ_c9BTen4P15lay_U-x2zE",
                data: "",
                success: function(result) {
                    // alert(result);
                    console.log(result.plus_code.compound_code);
                    if (result.plus_code.compound_code == null) {
                        alert('Something Went Wrong');
                    } else {
                        $('#openlocationcodeadd').val(result.plus_code.compound_code);
                    }
                }
            });

        });

        // Function to set a static location as a fallback
        function setStaticLocation() {
            var staticLat = 10.315934455764896; // Replace with your static latitude
            var staticLng = 123.88748969840327; // Replace with your static longitude

            // Set the static location as the center of the click map
            clickMap.setCenter(new google.maps.LatLng(staticLat, staticLng));

            // Add a marker for the static location
            new google.maps.Marker({
                position: {
                    lat: staticLat,
                    lng: staticLng
                },
                map: clickMap,
                title: 'Static Location'
            });
        }
    </script>

    <script type="text/javascript">
        // add row
        $(document).on('click', '#addRow', function() {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<div class="input-group mb-3 row">';
            html += '<div class="form-group col-md-5">';
            html +=
                '<input type="text" class="form-control" class="fld" name="gas_tit[]" id="field-name" value="" placeholder="Title">';
            html += '</div>';
            html += '<div class="form-group col-md-5">';
            html +=
                '<input type="text" class="form-control" class="fld" name="gas_pri[]" id="field-name" value="" placeholder="Price">';
            html += '</div>';
            html += '<div class="input-group-append col-md-2">';
            html +=
                '<img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon" class="cusicon" id="removeRow">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $(this).prev('#newRow').append(html);
        });

        // remove row
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    </script>
    <script type="text/javascript">
        $(document).on('click', '#addRow2', function() {
            var html2 = '';
            html2 += '<div id="inputFormRow2">';
            html2 += '<div class="input-group mb-3 row">';
            html2 += '<div class="form-group col-md-5">';
            html2 +=
                '<input type="text" class="form-control" class="fld" name="die_tit[]" id="field-name" value="" placeholder="Title">';
            html2 += '</div>';
            html2 += '<div class="form-group col-md-5">';
            html2 +=
                '<input type="text" class="form-control" class="fld" name="die_pri[]" id="field-value" placeholder="Price">';
            html2 += '</div>';
            html2 += '<div class="input-group-append col-md-2">';
            html2 +=
                '<img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon" class="cusicon" id="removeRow2">';
            html2 += '</div>';
            html2 += '</div>';
            html2 += '</div>';

            // $('#newRow2').append(html2);
            $(this).prev('#newRow2').append(html2);
        });

        // remove row
        $(document).on('click', '#removeRow2', function() {
            $(this).closest('#inputFormRow2').remove();
        });
    </script>

    <script>
        $("form#formdataaddmaunally").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            // console.log(formData);
            Loader();
            $.ajax({
                url: "{{ route('addnewstation') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    $(document).find('.errornull').text('');
                },
                success: function(data) {

                    Loaderclose();

                    if (data.error) {
                        console.log(data.error);
                        // $('#addmanerror').html(data.error);
                        // Loaderclose();
                        $.each(data.error, function(prefix, val){
                            $('span#'+prefix+'_error').html(val[0]);
                        });
                        // $('#brand_error').html(data.error);
                        // swal("Fill The Field!", "" + data.error);
                    }
                    if(data.otherbrandalreadyadded){
                        $('#otherbrandalreadyadded').html(data.otherbrandalreadyadded);
                    }
                    if(data.otherbrandlogo_error){
                        $('#otherbrandlogo_error').html(data.otherbrandlogo_error);
                    }
                    if(data.otherbrand_error){
                        $('#otherbrand_error').html(data.otherbrand_error);
                    }
                    if(data.diesel_error){
                        $('#diesel_error').html(data.diesel_error);
                    }
                    if(data.gasoline_error){
                        $('#gasoline_error').html(data.gasoline_error);
                    }


                    if(data.alreadyaddederror){
                        swal({
                            title: "This location has been already added.",
                            // text: "That thing is still around?",
                        }).then(function() {
                            location.reload();
                        });
                    }
                    if (data.new) {
                        console.log(data.new.id);
                        var newid = data.new.id;
                        // localStorage.setItem("newid",newid);
                        // Loaderclose();
                        // swal({
                        //     title: "Success",
                        //     text: "Data added successfully!",
                        //     type: "success"
                        //     },
                        // function(){
                        //     location.reload();
                        // }
                        // );
                        swal({
                            title: "Data has been added.",
                            // text: "That thing is still around?",
                            icon: "success",
                        }).then(function() {
                            localStorage.setItem("newaddid", newid);
                            location.reload();
                        });
                        // location.reload();
                        // window.location = '/';
                        // alert(data);
                        // var id = data.new.id;
                        // console.log(id);
                        // $.ajax({
                        //     type: "GET",
                        //     url: "{{ route('storepageright') }}",
                        //     data: {
                        //         "_token": "{{ csrf_token() }}",
                        //         id: id,
                        //     },
                        //     dataType: "json",
                        //     success: function(data) {
                        //         $('.display-data').html(data.data);
                        //     }
                        // });
                    }
                    if(data = false){
                        swal({
                            title: "Something Went Wrong!",
                            // text: "That thing is still around?",
                            icon: "error",
                        }).then(function() {
                            location.reload();
                        });
                    }
                },
            });
        });
    </script>

    <script type="text/javascript">
        // add row
        $(document).on('click', '#addRowedit', function() {
            var html3 = '';
            html3 += '<div id="inputFormRowedit">';
            html3 += '<div class="input-group mb-3 row">';
            html3 += '<div class="col-md-5">';
            html3 +=
                '<input type="text" class="fldforproducttitle" name="gasolinet[]" id="field-name" placeholder="Title">';
            html3 += '</div>';
            html3 += '<div class="col-md-5">';
            html3 +=
                '<input type="text" class="fldforproductvalue" name="gasolinep[]" id="field-value" placeholder="Price">';
            html3 += '</div>';
            html3 += '<div class="col-md-2 d-flex justify-content-center align-items-center">';
            html3 +=
                '<img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon" class="cusicon" id="removeRowedit">';
            html3 += '</div>';
            html3 += '</div>';
            html3 += '</div>';
            $(this).prev('#newRowedit').append(html3);
        });

        // remove row
        $(document).on('click', '#removeRowedit', function() {
            $(this).closest('#inputFormRowedit').remove();
        });
    </script>
    <script type="text/javascript">
        // add row
        $(document).on('click', '#addRowedit2', function() {
            var html4 = '';
            html4 += '<div id="inputFormRowedit2">';
            html4 += '<div class="input-group mb-3 row">';
            html4 += '<div class="col-md-5">';
            html4 +=
                '<input type="text" class="fldforproducttitle"name="dieselt[]" id="field-name" placeholder="Title">';
            html4 += '</div>';
            html4 += '<div class="col-md-5">';
            html4 +=
                '<input type="text" class="fldforproductvalue2" name="dieselp[]" id="field-value" placeholder="Price">';
            html4 += '</div>';
            html4 += '<div class="col-md-2 d-flex justify-content-center align-items-center">';
            html4 +=
                '<img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon" class="cusicon" id="removeRowedit2">';
            html4 += '</div>';
            html4 += '</div>';
            html4 += '</div>';
            $(this).prev('#newRowedit2').append(html4);
        });

        // remove row
        $(document).on('click', '#removeRowedit2', function() {
            $(this).closest('#inputFormRowedit2').remove();
        });
    </script>

    <script>
        $(document).on('keyup', '#search', function() {
            var search = $(this).val(); // not put "var" before this variable...
            console.log(search);
        });
    </script>

<script>
    @foreach ($storedatasforapps as $storedatasforapp)
        $(document).ready(function(){
            // UTC time string
            const utc = new Date(<?php echo strtotime($storedatasforapp->custom)*1000; ?>);
            const utcTimeString = utc.toUTCString();
            console.log(utcTimeString);

            const utcMoment = moment.utc(utcTimeString);
            console.log(utcMoment);

            // Convert UTC time to local time
            const localMoment = utcMoment.local();

            // Display the local time
            console.log("Local time: " + localMoment.format());
            $('.updatedbytxforapp{{ $storedatasforapp->id }}').html('Last updated by {{ $storedatasforapp->nick_name }} on '+ localMoment.format('MMM. DD, YYYY') + ' at ' + localMoment.format('hh:mm a'));
        })
    @endforeach
</script>


</body>

</html>
