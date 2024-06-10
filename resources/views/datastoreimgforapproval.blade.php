@if (isset($rightdatastoreimgapp))
    <div id="rightsidebox">
        <div class="d-flex justify-content-center alert-box">
            {{-- <span id="updatesuccess" class="successtx my-2"></span> --}}
        </div>
        <div class="row headingbox">
            <div class="col d-flex justify-content-start align-items-center">
                <div class="brand_logorightbox">
                    @if ($rightdatastoreimgapp->storedata->brand_logo == null)
                        <img src="{{ asset('assets/images/brandlogostatic.svg') }}" alt="brand_logo"
                            class="brand_logoimgright">
                    @else
                        <img src="{{ asset('/public/images/brandlogo/' . $rightdatastoreimgapp->storedata->brand_logo) }}"
                            alt="brand_logo" class="brand_logoimgright">
                        {{-- <img src="{{ asset('images/brandlogo/' . $rightdatastoreimgapp->storedata->brand_logo) }}" alt="brand_logo"
                            class="brand_logoimgright"> --}}
                    @endif
                </div>
            </div>
            <div class="col-md-9 d-flex justify-content-start align-items-center">
                <span class="rightheader">{{ $rightdatastoreimgapp->storedata->store_name }}</span>
            </div>
            <div class="col">

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
                <div class="modal fade" id="rejectmodal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title" id="exampleModalLabel">
                                    <span class="modalhead">Reject Approval Data</span>
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
                                        <span class="alerttx">Are you sure you want to Reject this Approval Data?</span>
                                    </div>
                                    <div class="mt-4 d-flex justify-content-between">
                                        <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                            data-bs-dismiss="modal">
                                            <span class="cancelbuttpoptx">Cancel</span>
                                        </div>
                                        <div class="removepopup curpoi d-flex justify-content-center align-items-center"
                                            id="rejectconfirm" data-bs-dismiss="modal">
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
                <button class="editbox" id="approve">
                    <span class="edittx">Approve</span>
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
                        <span class="name2">{{ $rightdatastoreimgapp->storedata->store_name }}</span>
                    </div>
                </div>

                <div class="headingbox">
                    <div>
                        <span class="storenametx">Update request by</span>
                    </div>
                    <div class="my-1">
                        <span class="name2">{{ $rightdatastoreimgapp->modify_name }}</span>
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
                                    <span
                                        class="locationtx">{{ $rightdatastoreimgapp->storedata->store_address }}</span>
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

            </div>
        </div>
        <div class="row">
            <div class="headingbox col-md-6">
                <div>
                    <span class="storenametx">Current Image</span>
                </div>
                <div class="detail_photo_box my-1">
                    @if ($rightdatastoreimgapp->storedata->store_image == null)
                        <img src="{{ asset('assets/images/imagenotavailable.png') }}" alt="detail_photo"
                            class="detail_photo">
                    @else
                        <a href="{{ asset('public/images/storeimage/' . $rightdatastoreimgapp->storedata->store_image) }}"
                            target="blank"><img
                                src="{{ asset('public/images/storeimage/' . $rightdatastoreimgapp->storedata->store_image) }}"
                                alt="detail_photo" class="detail_photo"></a>
                    @endif
                </div>
            </div>
            <div class="headingbox col-md-6">
                <div>
                    <span class="storenametx">Requested Image</span>
                </div>
                <div class="detail_photo_box my-1">
                    @if ($rightdatastoreimgapp->storefornt_img == null)
                        <img src="{{ asset('assets/images/imagenotavailable.png') }}" alt="detail_photo"
                            class="detail_photo">
                    @else
                        <a href="{{ asset('public/images/storeimage/' . $rightdatastoreimgapp->storefornt_img) }}"
                            target="blank"><img
                                src="{{ asset('public/images/storeimage/' . $rightdatastoreimgapp->storefornt_img) }}"
                                alt="detail_photo" class="detail_photo"></a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#rejectconfirm').click(function() {
            var id = {{ $rightdatastoreimgapp->id }}
            // console.log(id);

            Loader();
            $.ajax({
                url: "{{ route('rejectstoreimgreq') }}",
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
                            title: "Request has been rejected.",
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    }
                    if (data == false) {
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
        $('#approve').click(function() {
            var id = {{ $rightdatastoreimgapp->id }}
            // console.log(id);

            Loader();
            $.ajax({
                url: "{{ route('approvestoreimgreq') }}",
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
                    if (data == false) {
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
@endif
