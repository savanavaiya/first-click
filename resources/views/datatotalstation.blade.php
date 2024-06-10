@if (isset($rightdata))
    <div id="rightsidebox">
        <div class="d-flex justify-content-center alert-box">
            {{-- <span id="updatesuccess" class="successtx my-2"></span> --}}
        </div>
        <div class="row headingbox">
            <div class="col d-flex justify-content-start align-items-center">
                <div class="brand_logorightbox">
                    @if ($rightdata->brand_logo == null)
                        <img src="{{ asset('assets/images/brandlogostatic.svg') }}" alt="brand_logo"
                            class="brand_logoimgright">
                    @else
                        <img src="{{ asset('/public/images/brandlogo/' . $rightdata->brand_logo) }}" alt="brand_logo"
                            class="brand_logoimgright">
                        {{-- <img src="{{ asset('images/brandlogo/' . $rightdata->brand_logo) }}" alt="brand_logo"
                            class="brand_logoimgright"> --}}
                    @endif
                </div>
            </div>
            <div class="col-md-9 d-flex justify-content-start align-items-center">
                <span class="rightheader">{{ $rightdata->store_name }}</span>
            </div>
            {{-- <div class="col">

            </div> --}}

            <div class="col d-flex justify-content-end align-items-center">
                <button class="editbox" id="editoption">
                    <img src="{{ asset('assets/images/edit2.svg') }}" alt="edit" class="editlogo">
                    <span class="edittx">Edit</span>
                </button>
            </div>
            <div class="col d-flex justify-content-end align-items-center">
                <button class="deletebox" data-bs-toggle="modal" data-bs-target="#removemodal">
                    <img src="{{ asset('assets/images/remove.svg') }}" alt="delete" class="editlogo">
                    <span class="deletetx">Delete</span>
                </button>

                {{-- modal --}}
                <div class="modal fade" id="removemodal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title" id="exampleModalLabel">
                                    <span class="modalhead">Delete Station</span>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="bordcuss">
                                <div class="modal-body p-0">
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center mb-5">
                                        <span class="alerttx">Are you sure you want to Delete this station?</span>
                                    </div>
                                    <div class="mt-4 d-flex justify-content-between">
                                        <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                            data-bs-dismiss="modal">
                                            <span class="cancelbuttpoptx">Cancel</span>
                                        </div>
                                        <div class="removepopup curpoi d-flex justify-content-center align-items-center"
                                            id="removeconfirm" data-bs-dismiss="modal">
                                            <span class="removepopuptx">Yes, Delete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="headingbox">
            <div>
                <span class="storenametx">Station Name</span>
            </div>
            <div class="my-1">
                <span class="name2">{{ $rightdata->store_name }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="headingbox">
                    <div>
                        <span class="location">Location</span>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="my-1 d-flex align-items-center">
                                <div class="localogbox">
                                    <img src="{{ asset('assets/images/locationmainpart.svg') }}" alt="location"
                                        class="localogo">
                                </div>
                                <div>
                                    <span class="locationtx">{{ $rightdata->store_address }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end">

                        </div>
                    </div>
                </div>

                @if ($rightdata->landmarks != null)
                    <div class="headingbox">
                        <div>
                            <span class="otherinfo">Landmarks</span>
                        </div>
                        <div class="my-1">
                            <span class="otherinfotx">{{ $rightdata->landmarks }}</span>
                        </div>
                    </div>
                @else

                @endif

                <div class="headingbox">
                    <div>
                        <span class="otherinfo">City/Province</span>
                    </div>
                    <div class="my-1">
                        <span class="otherinfotx">{{ $rightdata->city == null ? '-' : $rightdata->city }}</span>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="distx">Diesel Prices</span>
                    </div>
                    <div class="my-1">
                        <div class="d-flex">
                            @foreach ($rightdata->diesel as $key2 => $item2)
                                <div class="borderright">
                                    <div>
                                        <span class="dieseltitle">{{ $key2 }}</span>
                                    </div>
                                    <div>
                                        <span class="dieselvalue">{{ $item2 }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="gasotx">Gasoline Prices</span>
                    </div>
                    <div class="my-1">
                        <div class="d-flex">
                            @foreach ($rightdata->gasoline as $key => $item)
                                <div class="borderright">
                                    <div>
                                        <span class="gasolintital">{{ $key }}</span>
                                    </div>
                                    <div>
                                        <span class="gasolinevalue">{{ $item }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="headingboxtime">
                    <div>
                        {{-- <span class="updatedbytx">Last updated by {{ $rightdata->modify_name }} on {{ $rightdata->updated_at->format('M. d, Y') }} at {{ $rightdata->updated_at->format('H:i A') }}</span> --}}
                        <span class="updatedbytx"></span>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="otherinfo">Status</span>
                    </div>
                    <div class="my-1">
                        <span class="otherinfotx">{{ $rightdata->status }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="headingbox">
                    <div>
                        <span class="onmaptx">On Map</span>
                    </div>
                    <div class="my-2">
                        <div class="map_imagebox">
                            <div id="database-map" style="height: 100%;width: 100%"></div>

                            <script>
                                // Initialize the database map
                                var databaseMap = new google.maps.Map(document.getElementById('database-map'), {
                                    center: {
                                        lat: {{ $rightdata->store_location_latitude }},
                                        lng: {{ $rightdata->store_location_longitude }}
                                    }, // Set your default center
                                    zoom: 15,
                                });

                                // Add markers for database coordinates
                                new google.maps.Marker({
                                    position: {
                                        lat: {{ $rightdata->store_location_latitude }},
                                        lng: {{ $rightdata->store_location_longitude }}
                                    },
                                    map: databaseMap,
                                    title: 'Database Marker'
                                });
                            </script>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- <div>
                                <span class="onmaptx">On Map</span>
                            </div>
                            <div class="my-2">
                                <div class="map_imagebox">
                                    <div id="database-map" style="height: 100%;width: 100%"></div>

                                    <script>
                                        // Initialize the database map
                                        var databaseMap = new google.maps.Map(document.getElementById('database-map'), {
                                            center: {
                                                lat: {{ $rightdata->store_location_latitude }},
                                                lng: {{ $rightdata->store_location_longitude }}
                                            }, // Set your default center
                                            zoom: 15,
                                        });

                                        // Add markers for database coordinates
                                        new google.maps.Marker({
                                            position: {
                                                lat: {{ $rightdata->store_location_latitude }},
                                                lng: {{ $rightdata->store_location_longitude }}
                                            },
                                            map: databaseMap,
                                            title: 'Database Marker'
                                        });
                                    </script>

                                </div>
                            </div> --}}
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="storeimage">Store Image</span>
                                </div>
                            </div>
                            <div class="my-2">
                                <div class="store_imagebox">
                                    @if ($rightdata->store_image == null)
                                        <img src="{{ asset('assets/images/imagenotavailable.png') }}" alt="store_image"
                                            class="store_image">
                                    @else
                                        <a href="{{ asset('public/images/storeimage/' . $rightdata->store_image) }}" target="blank"><img src="{{ asset('public/images/storeimage/' . $rightdata->store_image) }}"
                                            alt="store_image" class="store_image"></a>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="d-none" id="rightboxedit">
        <div class="d-flex justify-content-center alert-box">
            {{-- <span id="updateerror" class="errortx my-2"></span> --}}
            <span id="id_errorupd" class="errortx errornull"></span>
            <span id="brand_errorupd" class="errortx errornull"></span>
            <span id="store_location_errorupd" class="errortx errornull"></span>
        </div>
        <form id="formupdatedata" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="" value="{{ $rightdata->id }}">
            <input type="hidden" name="brand" id="" value="{{ $rightdata->brand }}">
            <input type="hidden" name="store_location" id="" value="{{ $rightdata->store_location }}">
            <div class="row headingbox">
                <div class="col-md-10 d-flex justify-content-start align-items-center">
                    <span class="rightbxeditheade">Edit Station Details</span>
                </div>

                <div class="col-md-2 d-flex justify-content-end align-items-center">
                    <button class="updatebox" type="submit">
                        <img src="{{ asset('assets/images/updateicon.svg') }}" alt="edit" class="editlogo">
                        <span class="updtx">Update</span>
                    </button>
                </div>
            </div>
            <div class="row headingbox">
                <div class="col-md-1 d-flex justify-content-start align-items-center">
                    <div class="brand_logorightbox">
                        @if ($rightdata->brand_logo == null)
                            <img src="{{ asset('assets/images/brandlogostatic.svg') }}" alt="brand_logo"
                                class="brand_logoimgright">
                        @else
                            <img src="{{ asset('/public/images/brandlogo/' . $rightdata->brand_logo) }}"
                                alt="brand_logo" class="brand_logoimgright">
                            {{-- <img src="{{ asset('images/brandlogo/' . $rightdata->brand_logo) }}" alt="brand_logo"
                                class="brand_logoimgright"> --}}
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <span class="storenametx">Brand Name</span>
                    </div>
                    <div class="my-1">
                        <span class="name2">{{ $rightdata->brand }}</span>
                    </div>
                </div>
                <div class="col-md-9">
                    <div>
                        <span class="storenametx">Store Name</span>
                    </div>
                    <div class="my-1">
                        <input type="text" name="store_name" id="" class="fld2"
                            value="{{ $rightdata->store_name }}">
                        <span id="store_name_errorupd" class="errortx errornull"></span>
                    </div>
                </div>
            </div>

            <div class="headingbox">
                <div>
                    <span class="location">Location</span>
                </div>
                <div class="my-1">
                    <div>
                        <input type="text" name="store_address" id="store_address" class="fld2"
                            value="{{ $rightdata->store_address }}">
                        <span id="store_address_errorupd" class="errortx errornull"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="headingbox">
                        <div>
                            <span class="otherinfo">Landmarks</span>
                        </div>
                        <div class="my-1">
                            <input type="text" name="landmarks" id="" class="fld2"
                                value="{{ $rightdata->landmarks }}">
                            <span id="otherinfo_errorupd" class="errortx errornull"></span>
                        </div>
                    </div>

                    <div class="headingbox">
                        <div>
                            <span class="otherinfo">City/Province</span>
                        </div>
                        <div class="my-1">
                            <input type="text" name="city" id="" class="fld2"
                                value="{{ $rightdata->city }}">
                            <span id="city_errorupd" class="errortx errornull"></span>
                        </div>
                    </div>

                    <div class="headingbox">
                        <div>
                            <span class="otherinfo">Status</span>
                        </div>
                        <div class="my-1">
                            <input type="radio" name="status" id="upd" value="Updated"
                                {{ $rightdata->status == 'Updated' ? 'checked' : '' }}>
                            <label for="upd" class="storenametx" style="margin-right: 20px">Updated</label>

                            <input type="radio" name="status" id="notupd" value="Not Updated"
                                {{ $rightdata->status == 'Not Updated' ? 'checked' : '' }}>
                            <label for="notupd" class="storenametx" style="margin-right: 20px">Not Updated</label>
                        </div>
                    </div>

                    {{-- <div class="row headingbox">
                        <div class="col-md-6">
                            <div>
                                <span class="timetitle">Opening Time</span>
                            </div>
                            <div class="my-1">
                                <input type="text" name="opening_time" class="fld2"
                                    value="{{ $rightdata->opening_time }}">
                                <span id="opening_time_errorupd" class="errortx errornull"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <span class="timetitle">Closing Time</span>
                            </div>
                            <div class="my-1">
                                <input type="text" name="closing_time" class="fld2"
                                    value="{{ $rightdata->closing_time }}">
                                <span id="closing_time_errorupd" class="errortx errornull"></span>
                            </div>
                        </div>
                    </div> --}}

                    <div class="headingbox">
                        <div>
                            <span class="distx">Diesel Prices</span>
                        </div>
                        <div class="my-1">
                            <div class="cuswid">
                                @foreach ($rightdata->diesel as $key2 => $item2)
                                    {{-- <div class="borderright">
                                        <div>
                                            <span class="dieseltitle">{{ $key2 }}</span>
                                            <input type="text" name="dieselt[]" id="" class="fldforproducttitle"
                                                value="{{ $key2 }}">
                                        </div>
                                        <div>
                                            <span class="dieselvalue">{{ $item2 }}</span>
                                            <input type="text" name="dieselp[]" id="" class="fldforproductvalue2"
                                                value="{{ $item2 }}">
                                        </div>
                                    </div> --}}

                                    <div id="inputFormRowedit2">
                                        <div class="input-group mb-3 row">
                                            <div class="col-md-5">
                                                <input type="text" class="fldforproducttitle"name="dieselt[]"
                                                    id="field-name" value="{{ $key2 }}" placeholder="Title">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="fldforproductvalue2" name="dieselp[]"
                                                    id="field-value" placeholder="Price" value="{{ $item2 }}">
                                            </div>
                                            <div class="col-md-2 d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon"
                                                    class="cusicon" id="removeRowedit2">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="newRowedit2"></div>
                                <div id="addRowedit2" style="cursor: pointer;" class="d-flex">
                                    <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon" class="cusicon2">
                                    <span class="addmoretx">Add more</span>
                                </div>
                                <span id="dieselt_errorupd" class="errortx errornull"></span><br>
                                <span id="dieselp_errorupd" class="errortx errornull"></span>
                                <span id="die_upd_error" class="errortx errornull"></span>
                            </div>
                        </div>
                    </div>

                    <div class="headingbox">
                        <div>
                            <span class="gasotx">Gasoline Prices</span>
                        </div>
                        <div class="my-1">
                            <div class="cuswid">
                                @foreach ($rightdata->gasoline as $key => $item)
                                    {{-- <div class="borderright">
                                        <div>
                                            <span class="gasolintital">{{ $key }}</span>
                                            <input type="text" name="gasolinet[]" id="" class="fldforproducttitle" value="{{ $key }}">
                                        </div>
                                        <div>
                                            <span class="gasolinevalue">{{ $item }}</span>
                                            <input type="text" name="gasolinep[]" id="" class="fldforproductvalue" value="{{ $item }}">
                                        </div>
                                    </div> --}}

                                    <div id="inputFormRowedit">
                                        <div class="input-group mb-3 row">
                                            <div class="col-md-5">
                                                <input type="text" class="fldforproducttitle" name="gasolinet[]"
                                                    id="field-name" value="{{ $key }}" placeholder="Title">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="fldforproductvalue" name="gasolinep[]"
                                                    id="field-value" placeholder="Price" value="{{ $item }}">
                                            </div>
                                            <div class="col-md-2 d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon"
                                                    class="cusicon" id="removeRowedit">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="newRowedit"></div>
                                <div id="addRowedit" style="cursor: pointer;" class="d-flex">
                                    <img src="{{ asset('assets/images/plusicon.svg') }}" alt="plus_icon" class="cusicon2">
                                    <span class="addmoretx">Add more</span>

                                </div>
                                <span id="gasolinet_errorupd" class="errortx errornull"></span><br>
                                <span id="gasolinep_errorupd" class="errortx errornull"></span>
                                <span id="gas_upd_error" class="errortx errornull"></span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="headingbox">
                        <div>
                            <span class="onmaptx">On Map</span>
                        </div>
                        <div class="my-2">
                            <div class="map_imagebox2">
                                <div id="database-map2" style="height: 100%;width: 100%"></div>
                                <input type="hidden" name="openlocationcode" id="openlocationcode">
                                <span id="alreadyaddederror" class="errortx errornull"></span>

                                <input type="hidden" name="store_location_latitude" id="store_location_latitude">
                                <input type="hidden" name="store_location_longitude" id="store_location_longitude">

                                <script>
                                    // Initialize the database map
                                    var databaseMap = new google.maps.Map(document.getElementById('database-map2'), {
                                        center: {
                                            lat: {{ $rightdata->store_location_latitude }},
                                            lng: {{ $rightdata->store_location_longitude }}
                                        }, // Set your default center
                                        zoom: 15,
                                    });

                                    // Add a marker for the database coordinates
                                    var databaseMarker = new google.maps.Marker({
                                        position: {
                                            lat: {{ $rightdata->store_location_latitude }},
                                            lng: {{ $rightdata->store_location_longitude }}
                                        },
                                        map: databaseMap,
                                        title: 'Database Marker',
                                        draggable: true // Allow the marker to be draggable
                                    });

                                    // Add a click event listener to the map
                                    google.maps.event.addListener(databaseMap, 'click', function(event) {
                                        // Update the marker position to the clicked location
                                        databaseMarker.setPosition(event.latLng);
                                        // You may also want to update any associated form fields with the new coordinates
                                        updateFormFields(event.latLng.lat(), event.latLng.lng());
                                    });

                                    // Add a dragend event listener to the marker
                                    google.maps.event.addListener(databaseMarker, 'dragend', function(event) {
                                        // Get the new marker position after dragging
                                        var newPosition = databaseMarker.getPosition();
                                        // Update any associated form fields with the new coordinates
                                        updateFormFields(newPosition.lat(), newPosition.lng());
                                    });

                                    // Function to update form fields with new coordinates
                                    function updateFormFields(lat, lng) {
                                        // Example: Assuming you have input fields with IDs "latitude" and "longitude"

                                        // document.getElementById('lat').value = lat;
                                        // document.getElementById('long').value = lng;

                                        document.getElementById('store_location_latitude').value = lat;
                                        document.getElementById('store_location_longitude').value = lng;

                                        // You can add additional logic here as needed
                                        $.ajax({
                                            type: "GET",
                                            url: "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," +
                                            lng + "&key=AIzaSyAv8cnP0w7bCJ_c9BTen4P15lay_U-x2zE",
                                            data: "",
                                            success: function(result) {
                                                // alert(result);
                                                console.log(result.plus_code.compound_code);
                                                console.log(result);
                                                if (result.plus_code.compound_code == null) {
                                                    alert('Something Went Wrong');
                                                } else {
                                                    $('#openlocationcode').val(result.plus_code.compound_code);
                                                }
                                            }
                                        });

                                         // You can add additional logic here as needed
                                         $.ajax({
                                            type: "GET",
                                            url: "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," +
                                                lng + "&key=AIzaSyAv8cnP0w7bCJ_c9BTen4P15lay_U-x2zE",
                                            data: "",
                                            success: function(result) {
                                                // alert(result);
                                                console.log(result.plus_code.compound_code);
                                                if (result.plus_code.compound_code == null) {
                                                    alert('Something Went Wrong');
                                                } else {
                                                    $('#openlocationcode').val(result.plus_code.compound_code);
                                                }

                                                let cnt2 = result.results.length;
                                                let store_address = null;

                                                for (let a = 0; a < cnt2; a++) {
                                                    if (result.results[a].types.includes('street_address')) {
                                                        store_address = result.results[a].formatted_address;
                                                        break; // Exiting the loop since we found the street address
                                                    }
                                                }

                                                if (store_address === null) {
                                                    store_address = result.results[0].formatted_address;
                                                }

                                                $('#store_address').val(store_address);
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </form>

    </div>



    <script>
        $('#editoption').click(function() {
            $('#rightsidebox').addClass('d-none');
            $('#rightboxedit').removeClass('d-none');
            $('#rightboxedit').addClass('d-block');
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#selectedimgshow').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#file-input").change(function() {
            readURL(this);
        });
    </script>
    <script>
        //update record with ajax
        $("form#formupdatedata").submit(function(e) {
            e.preventDefault();
            // alert();
            var formData = new FormData(this);
            Loader();
            $.ajax({
                url: "{{ route('updatestationdata') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $(document).find('.errornull').text('');
                },
                success: function(data) {
                    // alert(data)
                    Loaderclose();
                    if (data.error) {
                        // alert(data.error);
                        // Loaderclose();
                        // alert(data.error);
                        // $('#updateerror').html(data.error);
                        // swal("Fill The Field!", "" + data.error);
                        $.each(data.error, function(prefix, val) {
                            $('span#' + prefix + '_errorupd').html(val[0]);
                        });
                    }
                    if (data.gas_upd_error) {
                        $('#gas_upd_error').html(data.gas_upd_error);
                        // alert(data.gas_upd_error);
                    }
                    if (data.alreadyaddederror) {
                        $('#alreadyaddederror').html(data.alreadyaddederror);
                        // alert(data.gas_upd_error);
                    }
                    if (data.die_upd_error) {
                        $('#die_upd_error').html(data.die_upd_error);
                        // alert(data.die_upd_error);
                    }
                    if (data.stationdata) {
                        // location.reload();
                        swal({
                            title: "Data has been updated.",
                            // text: "That thing is still around?",
                            icon: "success",
                        }).then(function() {
                            // $('#rightsidebox').removeClass('d-none');
                            // $('#rightsidebox').addClass('d-block');
                            // $('#rightboxedit').removeClass('d-block');
                            // $('#rightboxedit').addClass('d-none');
                            // $('#updatesuccess').html('Data update successfully');
                            var id = data.stationdata.id;
                            console.log(id);
                            localStorage.setItem("afterupdateid", id);
                            location.reload();
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

                        });
                    }
                    if (data == false) {
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

    <script>
        $('#removeconfirm').click(function() {
            // alert();
            // {{ route('deletestationdata') }}
            var id = {{ $rightdata->id }}
            Loader();
            $.ajax({
                type: "POST",
                url: "{{ route('deletestationdata') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id
                },
                success: function(data) {
                    Loaderclose();
                    if (data == false) {
                        swal({
                            title: "Something Went Wrong!",
                            // text: "That thing is still around?",
                            icon: "error",
                        }).then(function() {
                            location.reload();
                        });
                    }
                    if (data == true) {
                        // location.reload();
                        swal({
                            title: "Data has been deleted.",
                            // text: "That thing is still around?",
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // UTC time string
            const utc = new Date(<?php echo strtotime($rightdata->custom) * 1000; ?>);
            const utcTimeString = utc.toUTCString();
            console.log(utcTimeString);

            const utcMoment = moment.utc(utcTimeString);
            console.log(utcMoment);

            // Convert UTC time to local time
            const localMoment = utcMoment.local();

            // Display the local time
            console.log("Local time: " + localMoment.format());
            $('.updatedbytx').html('Last updated by {{ $rightdata->modify_name }} on ' + localMoment.format(
                'MMM. DD, YYYY') + ' at ' + localMoment.format('hh:mm a'));
        })
    </script>
@else
    <div class="container-fluid">
        <div class="nodatafound">
            <img src="{{ asset('assets/images/nodatafound.png') }}" alt="nodatafound" height="500px"
                width="500px">
            <div class="norecordtxpo">
                <span class="norecordfoundtx">
                    <center>No Record Found</center>
                </span>
            </div>
        </div>
    </div>


@endif
