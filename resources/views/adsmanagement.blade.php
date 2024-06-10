<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ads Management</title>
    <link rel="stylesheet" href="{{ asset('assets/css/adsmanagement.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
</head>

<body>

    @include('header')

    <div class="container-fluid my-2">
        <div class="row">
            <div class="col-md-8">
                <span class="headtx">Ads Management</span>
            </div>
            <div class="d-flex justify-content-center alert-box">
                @error('topadimage')
                    <span class="errortx my-2">{{ $message }}</span>
                @enderror
                @error('bottomadimage')
                    <span class="errortx my-2">{{ $message }}</span>
                @enderror
                @error('storeadimage')
                    <span class="errortx my-2">{{ $message }}</span>
                @enderror
                @if (session()->has('SUCCESS'))
                    <span class="successtx my-2">{{ session()->get('SUCCESS') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="row">
            {{-- <div class="col-md-1 borderleftbox">
                <div class="txdiv">
                    <span class="lefttxhigh">Home Page</span>
                </div>
            </div> --}}
            <div class="col-md-12 borderrightbox">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-5" style="margin-left: 30px">
                            <div class="mb-2">
                                <span class="partheadtx">Main page top banner</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <button class="butt" data-bs-toggle="modal" data-bs-target="#uploadimagetop">
                                    <span class="butttx">Upload Image</span>
                                </button>
                                <span class="errortx" style="margin-left: 10px">Please select 320px by 50px image</span>
                            </div>
                            @if ($datas == null)
                                <div class="bannerdotborderwhite d-flex justify-content-center align-items-center">
                                    <div class="p-4">
                                        {{-- <div class="d-flex justify-content-center mb-3">
                                            <img src="{{ asset('assets/images/backup.svg') }}" alt="backup"
                                                class="uploadicon">
                                        </div>
                                        <button class="butt" data-bs-toggle="modal" data-bs-target="#uploadimagetop">
                                            <span class="butttx">Upload Image</span>
                                        </button> --}}
                                        <div>
                                            <span class="noimagetx">No Image</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($datas->topadimage == null)
                                    <div class="bannerdotborderwhite d-flex justify-content-center align-items-center">
                                        <div class="p-4">
                                            {{-- <div class="d-flex justify-content-center mb-3">
                                                <img src="{{ asset('assets/images/backup.svg') }}" alt="backup"
                                                    class="uploadicon">
                                            </div>
                                            <button class="butt" data-bs-toggle="modal"
                                                data-bs-target="#uploadimagetop">
                                                <span class="butttx">Upload Image</span>
                                            </button> --}}
                                            <div>
                                                <span class="noimagetx">No Image</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bannerdotborder d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('public/images/ads/' . $datas->topadimage) }}"
                                            alt="adbannercutimage" class="adbannercutimage">
                                        {{-- <img src="{{ asset('images/ads/' . $datas->topadimage) }}" alt="adbannercutimage"
                                            class="adbannercutimage"> --}}

                                        <img src="{{ asset('assets/images/close-circle.svg') }}" alt="closebutton"
                                            class="closebuttforremoveimage" data-bs-toggle="modal"
                                            data-bs-target="#removetopimage">
                                    </div>
                                @endif
                            @endif

                        </div>
                        <div class="mb-5" style="margin-left: 30px">
                            <div class="mb-2">
                                <span class="partheadtx">Main page Bottom Banner</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <button class="butt" data-bs-toggle="modal" data-bs-target="#uploadimagebottom">
                                    <span class="butttx">Upload Image</span>
                                </button>
                                <span class="errortx" style="margin-left: 10px">Please select 320px by 100px
                                    image</span>
                            </div>
                            @if ($datas == null)
                                <div class="bannerdotborderwhite d-flex justify-content-center align-items-center">
                                    <div class="p-4">
                                        {{-- <div class="d-flex justify-content-center mb-3">
                                            <img src="{{ asset('assets/images/backup.svg') }}" alt="backup"
                                                class="uploadicon">
                                        </div>
                                        <button class="butt" data-bs-toggle="modal"
                                            data-bs-target="#uploadimagebottom">
                                            <span class="butttx">Upload Image</span>
                                        </button> --}}
                                        <div>
                                            <span class="noimagetx">No Image</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($datas->bottomadimage == null)
                                    <div class="bannerdotborderwhite d-flex justify-content-center align-items-center">
                                        <div class="p-4">
                                            {{-- <div class="d-flex justify-content-center mb-3">
                                                <img src="{{ asset('assets/images/backup.svg') }}" alt="backup"
                                                    class="uploadicon">
                                            </div>
                                            <button class="butt" data-bs-toggle="modal"
                                                data-bs-target="#uploadimagebottom">
                                                <span class="butttx">Upload Image</span>
                                            </button> --}}
                                            <div>
                                                <span class="noimagetx">No Image</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bannerdotborder d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('public/images/ads/' . $datas->bottomadimage) }}"
                                            alt="adbannercutimage" class="adbannercutimage">
                                        {{-- <img src="{{ asset('images/ads/' . $datas->bottomadimage) }}"
                                            alt="adbannercutimage" class="adbannercutimage"> --}}

                                        <img src="{{ asset('assets/images/close-circle.svg') }}" alt="closebutton"
                                            class="closebuttforremoveimage" data-bs-toggle="modal"
                                            data-bs-target="#removebottomimage">
                                    </div>
                                @endif
                            @endif

                        </div>

                        <div class="mb-5" style="margin-left: 30px">
                            <div class="mb-2">
                                <span class="partheadtx">Station page bottom banner</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <button class="butt" data-bs-toggle="modal" data-bs-target="#uploadimagestore">
                                    <span class="butttx">Upload Image</span>
                                </button>
                                <span class="errortx" style="margin-left: 10px">Please select 320px by 100px image</span>
                            </div>
                            @if ($datas == null)
                                    <div class="bannerdotborderwhite d-flex justify-content-center align-items-center">
                                        <div class="p-4">
                                            {{-- <div class="d-flex justify-content-center mb-3">
                                                <img src="{{ asset('assets/images/backup.svg') }}" alt="backup"
                                                    class="uploadicon">
                                            </div>
                                            <button class="butt" data-bs-toggle="modal"
                                                data-bs-target="#uploadimagestore">
                                                <span class="butttx">Upload Image</span>
                                            </button> --}}
                                            <div>
                                                <span class="noimagetx">No Image</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @if ($datas->storeadimage == null)
                                        <div class="bannerdotborderwhite d-flex justify-content-center align-items-center">
                                            <div class="p-4">
                                                {{-- <div class="d-flex justify-content-center mb-3">
                                                    <img src="{{ asset('assets/images/backup.svg') }}" alt="backup"
                                                        class="uploadicon">
                                                </div>
                                                <button class="butt" data-bs-toggle="modal"
                                                    data-bs-target="#uploadimagestore">
                                                    <span class="butttx">Upload Image</span>
                                                </button> --}}
                                                <div>
                                                    <span class="noimagetx">No Image</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bannerdotborder d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('public/images/ads/' . $datas->storeadimage) }}"
                                                alt="adbannercutimage" class="adbannercutimage">
                                            {{-- <img src="{{ asset('images/ads/' . $datas->storeadimage) }}"
                                            alt="adbannercutimage" class="adbannercutimage"> --}}

                                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="closebutton"
                                                class="closebuttforremoveimage" data-bs-toggle="modal"
                                                data-bs-target="#removestoreimage">
                                        </div>
                                    @endif
                                @endif
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="borderbox">
                            <div class="mb-3">
                                <span class="previewtx">Preview</span>
                            </div>
                            {{-- <div>
                                <span class="tx">Imperdiet in odio enim in imperdiet sit fames donec
                                    ultrices.</span>
                            </div>
                            <div class="mb-3">
                                <span class="tx">Congue ac dictumst praesent hac pellentesque</span>
                            </div> --}}


                            <div class="d-flex justify-content-center align-items-center">
                                <div class="position-relative">
                                    <img src="{{ asset('assets/images/iPhone14.png') }}" alt="iphone14"
                                        class="iphone">

                                    @if ($datas == null)
                                        <div class="bannerwhitetop">
                                        </div>
                                    @else
                                        @if ($datas->topadimage == null)
                                            <div class="bannerwhitetop">
                                            </div>
                                        @else
                                            <div class="bannerwhitetop">
                                            </div>
                                            <div class="adbannnerphonetop">
                                                <img src="{{ asset('public/images/ads/' . $datas->topadimage) }}"
                                                    alt="adbaannerphoneview" class="adbannnerphoneimg">
                                                {{-- <img src="{{ asset('images/ads/' . $datas->topadimage) }}"
                                                    alt="adbaannerphoneview" class="adbannnerphoneimg"> --}}
                                            </div>
                                        @endif
                                    @endif


                                    @if ($datas == null)
                                        <div class="bannerwhitebottom">
                                        </div>
                                    @else
                                        @if ($datas->bottomadimage == null)
                                            <div class="bannerwhitebottom">
                                            </div>
                                        @else
                                            <div class="adbannnerphonebottom">
                                                <img src="{{ asset('public/images/ads/' . $datas->bottomadimage) }}"
                                                    alt="adbaannerphoneview" class="adbannnerphoneimgbottom">
                                                {{-- <img src="{{ asset('images/ads/' . $datas->bottomadimage) }}"
                                                    alt="adbaannerphoneview" class="adbannnerphoneimg"> --}}
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="position-relative" style="margin-left: 50px">
                                    <img src="{{ asset('assets/images/iphonestationpage.png') }}" alt="iphone14"
                                        class="iphone2">

                                    @if ($datas == null)
                                        <div class="bannerwhitestore">
                                        </div>
                                    @else
                                        @if ($datas->storeadimage == null)
                                            <div class="bannerwhitestore">
                                            </div>
                                        @else
                                            <div class="bannerwhitestore">
                                            </div>
                                            <div class="adbannnerphonestore">
                                                <img src="{{ asset('public/images/ads/' . $datas->storeadimage) }}"
                                                    alt="adbaannerphoneview" class="adbannnerphoneimgstore">
                                                {{-- <img src="{{ asset('images/ads/' . $datas->storeadimage) }}"
                                                        alt="adbaannerphoneview" class="adbannnerphoneimgstore"> --}}
                                            </div>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="uploadimagetop" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Upload Image</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('topadimage') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">Select Image</span><span class="errortx">Please select 320px by 50px
                                image</span>
                            <input type="file" class="fld" name="topadimage">
                        </div>

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


    <div class="modal fade" id="removetopimage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove top header ad?</span>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                data-bs-dismiss="modal">
                                <span class="cancelbuttpoptx">Cancel</span>
                            </div>
                            <a href="{{ route('removetopadimage') }}" style="text-decoration: none">
                                <div class="removepopup d-flex justify-content-center align-items-center"
                                    type="submit">
                                    <span class="removepopuptx">Yes, Remove</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadimagebottom" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Upload Image</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bottomadimage') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">Select Image</span><span class="errortx">Please select 320px by
                                100px
                                image</span>
                            <input type="file" class="fld" name="bottomadimage">
                        </div>

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

    <div class="modal fade" id="removebottomimage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove top header ad?</span>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                data-bs-dismiss="modal">
                                <span class="cancelbuttpoptx">Cancel</span>
                            </div>
                            <a href="{{ route('removebottomadimage') }}" style="text-decoration: none">
                                <div class="removepopup d-flex justify-content-center align-items-center"
                                    type="submit">
                                    <span class="removepopuptx">Yes, Remove</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadimagestore" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Upload Image</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('storeadimage') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">Select Image</span><span class="errortx">Please select 320px by
                                100px
                                image</span>
                            <input type="file" class="fld" name="storeadimage">
                        </div>

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

    <div class="modal fade" id="removestoreimage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove top header ad?</span>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbuttpop d-flex justify-content-center align-items-center"
                                data-bs-dismiss="modal">
                                <span class="cancelbuttpoptx">Cancel</span>
                            </div>
                            <a href="{{ route('removestoreadimage') }}" style="text-decoration: none">
                                <div class="removepopup d-flex justify-content-center align-items-center"
                                    type="submit">
                                    <span class="removepopuptx">Yes, Remove</span>
                                </div>
                            </a>
                        </div>
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
        setTimeout(function() {
            $('.alert-box').remove();
        }, 3000);
    </script>
</body>

</html>
