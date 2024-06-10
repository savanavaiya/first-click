<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brand Management</title>
    <link rel="stylesheet" href="{{ asset('assets/css/brandmanagement.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
</head>

<body>
    @include('header')

    <div class="container-fluid mt-3 mb-1">
        <div class="row">
            <div class="col-md-8">
                <span class="headtx">Brand Management</span>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <div class="newbutt" data-bs-toggle="modal" data-bs-target="#addbrandmodal">
                    <img src="{{ asset('assets/images/ic_Plus.svg') }}" alt="plus" class="plusimage">
                    <span class="newbutttx">New</span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center alert-box">
        @error('brand')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('brand_logo')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @if (session()->has('SUCCESS'))
            <span class="successtx my-2">{{ session()->get('SUCCESS') }}</span>
        @endif
        @if (session()->has('ERROR'))
            <span class="errortx my-2">{{ session()->get('ERROR') }}</span>
        @endif
    </div>

    <div class="container-fluid my-4">
        <div class="row">
            @if (!$branddatas->isEmpty())
                @foreach ($branddatas as $branddata)
                    <div class="col-md-2">
                        <div class="box">
                            <div class="d-flex justify-content-end">
                                <div style="cursor: pointer;margin-right:14px" id="edit" data-id="{{ $branddata->id }}" data-brand="{{ $branddata->brand }}" data-brand_logo="{{ $branddata->brand_logo }}" data-bs-toggle="modal" data-bs-target="#editbrandmodal">
                                    <img src="{{ asset('assets/images/edit-line.svg') }}" alt="more_option" height="20px" width="20px">
                                </div>
                                <div style="cursor: pointer;" id="remove" data-id="{{ $branddata->id }}" data-bs-toggle="modal" data-bs-target="#removebrandmodal">
                                    <img src="{{ asset('assets/images/remove.svg') }}" alt="more_option" height="20px" width="20px">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{-- <img src="{{ asset('images/brandlogo/'.$branddata->brand_logo) }}" alt="brand_logo" class="brand_logo"> --}}
                                <img src="{{ asset('public/images/brandlogo/'.$branddata->brand_logo) }}" alt="brand_logo" class="brand_logo">
                            </div>
                            <div class="d-flex justify-content-center mt-5">
                                <span class="brnadtx">{{ strtoupper($branddata->brand) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="container-fluid">
                    <div class="nodatafound">
                        <img src="{{ asset('assets/images/nodatafound.png') }}" alt="nodatafound" height="500px" width="500px">
                        <div class="norecordtxpo">
                            <span class="norecordfoundtx"><center>No Record Found</center></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <div class="modal fade" id="removebrandmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Remove Brand</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove this brand?</span>
                        </div>
                        <form id="removebd">
                            @csrf
                            <input type="hidden" name="id" id="removeid" value="">

                            <div class="mt-4 d-flex justify-content-between">
                                <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                    data-bs-dismiss="modal">
                                    <span class="cancelbuttpoptx">Cancel</span>
                                </div>
                                <button class="removepopup d-flex justify-content-center align-items-center"
                                    type="submit">
                                    <span class="removepopuptx">Yes, Remove</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addbrandmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Add New Brand</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addnewbrandfromportal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">Brand Name</span>
                            <input type="text" class="fld" name="brand" placeholder="Enter Brand Name">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Brand Logo</span>
                            <span class="errortx">Please select 250px by 250px image</span>
                            <input type="file" class="fld" name="brand_logo">
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbutt" data-bs-dismiss="modal">
                                <span class="canceltx">Cancel</span>
                            </div>
                            <button class="invcontbutt" type="submit">
                                <span class="invconttx">Add Brand</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editbrandmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Edit Brand</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editbd">
                        @csrf
                        <input type="hidden" name="id" id="editid">
                        <div class="mb-3">
                            <span class="fldname">Brand Name</span>
                            <input type="text" class="fld" name="brand" id="brand">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Brand Logo</span>
                            <span class="errortx">Please select 250px by 250px image</span>
                            <div class="my-2">
                                <img src="" class="editboximage" alt="brand_logo" id="brand_logo_img">
                                <input type="hidden" class="fld" name="brand_logo" id="brand_logo">
                            </div>
                            <div>
                                <input type="file" class="fld" name="brand_logo" id="brand_logo">
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbutt" data-bs-dismiss="modal">
                                <span class="canceltx">Cancel</span>
                            </div>
                            <button class="invcontbutt" type="submit">
                                <span class="invconttx">Update Brand</span>
                            </button>
                        </div>
                    </form>
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
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
    $("form#editbd").submit(function(e) {
        e.preventDefault();
        // alert();
        var formData = new FormData(this);
        Loader();

        $('#editbrandmodal').modal('hide');
        $.ajax({
            url: "{{ route('editnewbrandfromportal') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                // alert(data)
                Loaderclose();
                if (data == true) {
                    // location.reload();
                    swal({
                        title: "Brand has been updated.",
                        // text: "That thing is still around?",
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                }
                if (data == false) {
                    swal({
                        title: "This brand has been already added.",
                        icon: "error",
                    }).then(function() {
                        location.reload();
                    });
                }
                if(data == 'problem'){
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
        $("form#removebd").submit(function(e) {
            e.preventDefault();
            // alert();
            var formData = new FormData(this);
            Loader();

            $('#removebrandmodal').modal('hide');
            $.ajax({
                url: "{{ route('removebrandfromportal') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    // alert(data)
                    Loaderclose();
                    if (data == true) {
                        // location.reload();
                        swal({
                            title: "Brand has been deleted.",
                            // text: "That thing is still around?",
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    }
                    if (data == false) {
                        swal({
                            title: "You have already added station with this brand. So you can not delete this brand.",
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
        setTimeout(function() {
            $('.alert-box').remove();
        }, 3000);
    </script>
    <script>
        $(document).on("click", "#edit", function() {
            // alert();
            var editid = $(this).attr('data-id');
            console.log(editid);
            $("#editid").val(editid);

            var brand = $(this).attr('data-brand');
            console.log(brand);
            $("#brand").val(brand);

            var brand_logo = $(this).attr('data-brand_logo');
            console.log(brand_logo);
            $("#brand_logo").val(brand_logo);

            // $('#brand_logo_img').attr('src','http://127.0.0.1:8000/images/brandlogo/'+ brand_logo +'');
            $('#brand_logo_img').attr('src','https://firstclick-v1.brijeshnavadiya.com/public/images/brandlogo/'+ brand_logo +'');
            // $('#brand_logo_img').attr('src','https://admin.firstclick.ph/public/images/brandlogo/'+ brand_logo +'');
        });
    </script>
    <script>
        $(document).on("click", "#remove", function() {
            // alert();
            var removeid = $(this).attr('data-id');
            console.log(removeid);
            $("#removeid").val(removeid);

        });
    </script>
</body>

</html>
