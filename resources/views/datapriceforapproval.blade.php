@if (isset($rightdatapriceapp))
    <div id="rightsidebox">
        <div class="d-flex justify-content-center alert-box">
            {{-- <span id="updatesuccess" class="successtx my-2"></span> --}}
        </div>
        <div class="row headingbox">
            <div class="col d-flex justify-content-start align-items-center">
                <div class="brand_logorightbox">
                    @if ($rightdatapriceapp->storedata->brand_logo == null)
                        <img src="{{ asset('assets/images/brandlogostatic.svg') }}" alt="brand_logo"
                            class="brand_logoimgright">
                    @else
                        <img src="{{ asset('/public/images/brandlogo/' . $rightdatapriceapp->storedata->brand_logo) }}" alt="brand_logo"
                            class="brand_logoimgright">
                        {{-- <img src="{{ asset('images/brandlogo/' . $rightdatapriceapp->storedata->brand_logo) }}" alt="brand_logo"
                            class="brand_logoimgright"> --}}
                    @endif
                </div>
            </div>

            <div class="col-md-8 d-flex justify-content-start align-items-center">
                <span class="rightheader">{{ $rightdatapriceapp->storedata->store_name }}</span>
            </div>

            <div class="col">

            </div>
            <div class="col d-flex justify-content-end align-items-center">
                <button class="editbox" id="editoption">
                    <img src="{{ asset('assets/images/edit2.svg') }}" alt="edit" class="editlogo">
                    <span class="edittx">Edit</span>
                </button>
            </div>

            <div class="col d-flex justify-content-end align-items-center">
                {{-- <a href="{{ route('rejectpricereq',$fulldata->id) }}">
                    <div class="rejectbutt">
                        <span class="rejectbutttx">Reject</span>
                    </div>
                </a> --}}
                <button class="deletebox" data-bs-toggle="modal" data-bs-target="#rejectmodal">
                    <span class="deletetx">Reject</span>
                </button>


                {{-- modal --}}
                <div class="modal fade" id="rejectmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title" id="exampleModalLabel">
                                    <span class="modalhead">Reject Approval Data</span>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="bordcuss">
                                <div class="modal-body p-0">
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center mb-5">
                                        <span class="alerttx">Are you sure you want to Reject this Approval Data?</span>
                                    </div>
                                    <div class="mt-4 d-flex justify-content-between">
                                        <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                            data-bs-dismiss="modal">
                                            <span class="cancelbuttpoptx">Cancel</span>
                                        </div>
                                        <div class="removepopup curpoi d-flex justify-content-center align-items-center" id="rejectconfirm" data-bs-dismiss="modal">
                                            <span class="removepopuptx">Yes, Delete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-flex justify-content-end align-items-center">
                {{-- <a href="{{ route('approvepricereq',$fulldata->id) }}" onclick="Loader()">
                    <div class="approvebutt">
                        <span class="approvebutttx">Approve</span>
                    </div>
                </a> --}}
                <button class="apprbox" id="approve">
                    <span class="apprtx">Approve</span>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="headingbox">
                    <div>
                        <span class="storenametx">Station Name</span>
                    </div>
                    <div class="my-1">
                        <span class="name2">{{ $rightdatapriceapp->storedata->store_name }}</span>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="storenametx">Update request by</span>
                    </div>
                    <div class="my-1">
                        <span class="name2">{{ $rightdatapriceapp->modify_name }}</span>
                    </div>
                </div>

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
                                    <span class="locationtx">{{ $rightdatapriceapp->storedata->store_address }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end">
                            {{-- <div class="mapiconbox">
                                <img src="{{ asset('assets/images/map.svg') }}" alt="edit" class="maplogo">
                                <span class="maplogotx">View Map Pin</span>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="headingbox">
                            <div class="mb-2">
                                <span class="location">Existing price</span>
                            </div>
                            <div class="paddclass mb-4">
                                <div>
                                    <span class="distx">Diesel Prices</span>
                                </div>
                                <div class="my-1">
                                    <div class="d-flex">
                                        @foreach ($rightdatapriceapp->storedata->diesel as $key2 => $item2)
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
                            <div class="paddclass">
                                <div>
                                    <span class="gasotx">Gasoline Prices</span>
                                </div>
                                <div class="my-1">
                                    <div class="d-flex">
                                        @foreach ($rightdatapriceapp->storedata->gasoline as $key => $item)
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
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="headingbox">
                            <div class="mb-2">
                                <span class="location">Requested price</span>
                            </div>

                            <div class="paddclass mb-4">
                                <div>
                                    <span class="distx">Diesel Prices</span>
                                </div>
                                <div class="my-1">
                                    <div class="d-flex">
                                        @foreach ($rightdatapriceapp->diesel as $key2 => $item2)
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
                            <div class="paddclass">
                                <div>
                                    <span class="gasotx">Gasoline Prices</span>
                                </div>
                                <div class="my-1">
                                    <div class="d-flex">
                                        @foreach ($rightdatapriceapp->gasoline as $key => $item)
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
                        </div>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="storenametx">Feedback/Comments</span>
                    </div>
                    <div class="my-1">
                        <span class="name2">{{ $rightdatapriceapp->comments }}</span>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="storenametx">Date of submission</span>
                    </div>
                    <div class="my-1">
                        {{-- <span class="name2">{{ $rightdatapriceapp->created_at }}</span> --}}
                        {{-- <span class="name2">{{ $rightdatapriceapp->created_at->format('M. d, Y') }} {{ $rightdatapriceapp->created_at->format('H:i A') }}<span> --}}
                        <span class="name2 updatedbytx"><span>
                    </div>
                </div>

            </div>
            <div class="col-md-6 headingbox">
                <div class="detail_photo_box">
                   @if ($rightdatapriceapp->detail_photo == null)
                        <img src="{{ asset('assets/images/imagenotavailable.png') }}" alt="detail_photo"
                        class="detail_photo">
                   @else
                        {{-- <a href="{{ asset('images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" target="blank"><img src="{{ asset('images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" alt="detail_photo" class="detail_photo"></a> --}}
                        <a href="{{ asset('public/images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" target="blank"><img src="{{ asset('public/images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" alt="detail_photo" class="detail_photo"></a>
                   @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-none" id="rightboxedit">
        <div class="d-flex justify-content-center alert-box">
            <span class="errortx my-2">

            </span>
        </div>

        <form id="formupdatedata">
            @csrf
            <div class="row headingbox">
                <input type="hidden" name="id" value="{{ $rightdatapriceapp->id }}">
                <div class="col-md-10 d-flex justify-content-start align-items-center">
                    <span class="rightbxeditheade">Edit Price For Approval Details</span>
                </div>

                <div class="col-md-2 d-flex justify-content-end align-items-center">
                    <button class="updatebox" type="submit">
                        <img src="{{ asset('assets/images/updateicon.svg') }}" alt="edit" class="editlogo">
                        <span class="updtx">Update</span>
                    </button>
                </div>
            </div>

            <div class="row headingbox">
                <div class="col d-flex justify-content-start align-items-center">
                    <div class="brand_logorightbox">
                        @if ($rightdatapriceapp->storedata->brand_logo == null)
                            <img src="{{ asset('assets/images/brandlogostatic.svg') }}" alt="brand_logo"
                                class="brand_logoimgright">
                        @else
                            <img src="{{ asset('/public/images/brandlogo/' . $rightdatapriceapp->storedata->brand_logo) }}" alt="brand_logo"
                                class="brand_logoimgright">
                            {{-- <img src="{{ asset('images/brandlogo/' . $rightdatapriceapp->storedata->brand_logo) }}" alt="brand_logo"
                                class="brand_logoimgright"> --}}
                        @endif
                    </div>
                </div>

                <div class="col-md-8 d-flex justify-content-start align-items-center">
                    <span class="rightheader">{{ $rightdatapriceapp->storedata->store_name }}</span>
                </div>

                <div class="col">

                </div>
                <div class="col">

                </div>
                <div class="col">

                </div>
                <div class="col">

                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="headingbox">
                        <div>
                            <span class="storenametx">Station Name</span>
                        </div>
                        <div class="my-1">
                            <span class="name2">{{ $rightdatapriceapp->storedata->store_name }}</span>
                        </div>
                    </div>

                    <div class="headingbox">
                        <div>
                            <span class="storenametx">Update request by</span>
                        </div>
                        <div class="my-1">
                            {{-- <span class="name2">{{ $rightdatapriceapp->modify_name }}</span> --}}
                            <input type="text" name="modify_name" id="" class="fld2"
                                value="{{ $rightdatapriceapp->modify_name }}">
                                <span id="modify_name_errorupd" class="errortx errornull"></span>
                        </div>
                    </div>

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
                                        <span class="locationtx">{{ $rightdatapriceapp->storedata->store_address }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                            </div>
                        </div>
                    </div>

                    <div class="headingbox">
                        <div>
                            <span class="distx">Diesel Prices</span>
                        </div>
                        <div class="my-1">
                            <div class="cuswid">
                                @foreach ($rightdatapriceapp->diesel as $key2 => $item2)

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
                                @foreach ($rightdatapriceapp->gasoline as $key => $item)

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

                    <div class="headingbox">
                        <div>
                            <span class="storenametx">Feedback/Comments</span>
                        </div>
                        <div class="my-1">
                            {{-- <span class="name2">{{ $rightdatapriceapp->comments }}</span> --}}
                            <input type="text" name="comments" id="" class="fld2"
                                value="{{ $rightdatapriceapp->comments }}">
                                <span id="comments_errorupd" class="errortx errornull"></span>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 headingbox">
                    <div class="detail_photo_box">
                       @if ($rightdatapriceapp->detail_photo == null)
                            <img src="{{ asset('assets/images/imagenotavailable.png') }}" alt="detail_photo"
                            class="detail_photo">
                       @else
                            {{-- <a href="{{ asset('images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" target="blank"><img src="{{ asset('images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" alt="detail_photo" class="detail_photo"></a> --}}
                            <a href="{{ asset('public/images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" target="blank"><img src="{{ asset('public/images/priceapproval/'.$rightdatapriceapp->detail_photo) }}" alt="detail_photo" class="detail_photo"></a>
                       @endif
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
        $('#rejectconfirm').click(function (){
            var id = {{ $rightdatapriceapp->id }}
            // console.log(id);

            Loader();
            $.ajax({
                url: "{{ route('rejectpricereq') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // alert(data)
                    Loaderclose();
                    if (data == true) {
                        swal({
                            title: "Request has been rejected. ",
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    }
                    if(data == false){
                        swal({
                            title: "Sorry, something went wrong. Please try again.",
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
        $('#approve').click(function (){
            var id = {{ $rightdatapriceapp->id }}
            // console.log(id);

            Loader();
            $.ajax({
                url: "{{ route('approvepricereq') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // alert(data)
                    Loaderclose();
                    if (data == true) {
                        swal({
                            title: "Request has been approved.",
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    }
                    if(data == false){
                        swal({
                            title: "Sorry, something went wrong. Please try again.",
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
    //update record with ajax
    $("form#formupdatedata").submit(function(e) {
        e.preventDefault();
        // alert();
        var formData = new FormData(this);
        Loader();
        $.ajax({
            url: "{{ route('updatepriceforappreq') }}",
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
                    $.each(data.error, function(prefix, val){
                        $('span#'+prefix+'_errorupd').html(val[0]);
                    });
                }
                if(data.gas_upd_error){
                    $('#gas_upd_error').html(data.gas_upd_error);
                }
                if(data.die_upd_error){
                    $('#die_upd_error').html(data.die_upd_error);
                }
                if (data.stationdata) {
                    swal({
                        title: "Data has been updated.",
                        icon: "success",
                    }).then(function() {
                        var id = data.stationdata.id;
                        console.log(id);
                        localStorage.setItem("afterupdateid", id);
                        location.reload();
                    });
                }
                if(data == false){
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
    $(document).ready(function() {
        // UTC time string
        const utc = new Date(<?php echo strtotime($rightdatapriceapp->created_at) * 1000; ?>);
        const utcTimeString = utc.toUTCString();
        console.log(utcTimeString);

        const utcMoment = moment.utc(utcTimeString);
        console.log(utcMoment);

        // Convert UTC time to local time
        const localMoment = utcMoment.local();

        // Display the local time
        console.log("Local time: " + localMoment.format());
        $('.updatedbytx').html( localMoment.format(
            'MMM. DD, YYYY') + '  ' + localMoment.format('hh:mm a'));
    })
</script>
@endif
