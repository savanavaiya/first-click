<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
</head>

<body>


    @include('header')

    <div class="d-flex justify-content-center alert-box">
        @error('firstname')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('lastname')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('phone')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('profile_image')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('current_version')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror

        @if (session()->has('SUCCESS'))
            <span class="successtx my-2">{{ session()->get('SUCCESS') }}</span>
        @endif
        @if (session()->has('ERROR'))
            <span class="errortx my-2">{{ session()->get('ERROR') }}</span>
        @endif
    </div>

    <div class="container-fluid row">
        <div class="col-md-6">
            <span class="headtx">Welcome to First click</span>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">

            <div class="syncbutt" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#versionModal">
                <span class="syncbutttx">Current App Version : {{ $appversion->current_version }}</span>
            </div>

            <a href="{{ route('allnotupdated') }}" onclick="Loader()" style="margin-right: 10px">
                <div class="syncbutt">
                    <span class="syncbutttx">Mark All as Not Updated</span>
                </div>
            </a>

            {{-- <a href="{{ route('sync') }}" onclick="Loader()"><img src="{{ asset('assets/images/1069839.png') }}" alt="syncicon" height="50px"
                    width="50px"></a> --}}
            <a href="{{ route('sync') }}" onclick="Loader()">
                <div class="syncbutt">
                    <span class="syncbutttx">Sync with Googlesheet</span>
                </div>
            </a>

        </div>
    </div>
    <div class="container-fluid">
        {{-- <div class="row px-4 py-3 borderupdown">
            <div class="col-md-2 p-4 borderright">
                <div class="imageround d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/ic_File.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('contributor','totaluser') }}">Total Users</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalcontributor }}</span>
                </div>
            </div>

            <div class="col-md-2 p-4 borderright">
                <div class="imageround2 d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/folder.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('contributor','activeuser') }}">Active Users</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalcontributoractive }}</span>
                </div>
            </div>

            <div class="col-md-2 p-4 borderright">
                <div class="imageround5 d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/ic_Decline.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('contributor','inactiveuser') }}">Inactive Users</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalcontributorinactive }}</span>
                </div>
            </div>

            <div class="col-md-2 p-4 borderright">
                <div class="imageround4 d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/ic_Projects.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('totalstation') }}">Total Station</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalstation }}</span>
                </div>
            </div>

            <div class="col-md-2 p-4 borderright">
                <div class="imageround6 d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/shopping.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('adsmanagement') }}">Ads Management</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalad + 1 }}</span>
                </div>
            </div>
            <div class="col-md-2 p-4">
                <div class="imageround3 d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/send.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('brandsmanagement') }}">Brand Management</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalbrand }}</span>
                </div>
            </div>
        </div>

        <div class="row px-4 py-3 borderupdown">
            <div class="col-md-2 p-4 borderright">
                <div class="imageround3 d-flex justify-content-center align-items-center mb-2">
                    <img src="{{ asset('assets/images/send.svg') }}" alt="" class="imageicon">
                </div>
                <div>
                    <span class="boxtx"><a href="{{ route('priceforapproval') }}">Price for Approval</a></span>
                </div>
                <div>
                    <span class="boxhead">{{ $totalpriceapp }}</span>
                </div>
            </div>
        </div> --}}

        <div class="row px-4 py-3">
            <div class="col-md-3">
                <a href="{{ route('contributor', 'totaluser') }}">
                    <div class="box">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="imageround d-flex justify-content-center align-items-center mb-2">
                                <img src="{{ asset('assets/images/ic_File.svg') }}" alt="" class="imageicon">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxtx">User
                                Management</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxhead">{{ $totalcontributor }}</span>
                        </div>
                    </div>
                </a>
            </div>
            {{-- <div class="col-md-2">
                <div class="box">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="imageround d-flex justify-content-center align-items-center mb-2">
                            <img src="{{ asset('assets/images/ic_File.svg') }}" alt="" class="imageicon">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxtx"><a href="{{ route('contributor','totaluser') }}">Total Users</a></span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxhead">{{ $totalcontributor }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="box">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="imageround2 d-flex justify-content-center align-items-center mb-2">
                            <img src="{{ asset('assets/images/folder.svg') }}" alt="" class="imageicon">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxtx"><a href="{{ route('contributor','activeuser') }}">Active Users</a></span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxhead">{{ $totalcontributoractive }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="box">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="imageround5 d-flex justify-content-center align-items-center mb-2">
                            <img src="{{ asset('assets/images/ic_Decline.svg') }}" alt="" class="imageicon">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxtx"><a href="{{ route('contributor','inactiveuser') }}">Inactive Users</a></span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxhead">{{ $totalcontributorinactive }}</span>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-3">
                <a href="{{ route('totalstation') }}">
                    <div class="box">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="imageround2 d-flex justify-content-center align-items-center mb-2">
                                <img src="{{ asset('assets/images/folder.svg') }}" alt="" class="imageicon">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            {{-- <span class="boxtx"><a href="{{ route('totalstation') }}">Station Management</a></span> --}}
                            <span class="boxtx">Station Management</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            {{-- <span class="boxhead">{{ $totalstation }} ({{ $totalapprovalstation }})</span> --}}
                            <span class="boxhead">{{ $totalstation }}</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-1">
                            {{-- <span class="boxhead2">For Approval {{ $totalapprovalstation }}</span> --}}
                            <span class="boxhead2">( For Approval : {{ $totalapprovalstation }} )</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('adsmanagement') }}">
                    <div class="box">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="imageround6 d-flex justify-content-center align-items-center mb-2">
                                <img src="{{ asset('assets/images/shopping.svg') }}" alt="" class="imageicon">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxtx">Ads Management</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxhead">{{ $totalad }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('brandsmanagement') }}">
                    <div class="box">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="imageround3 d-flex justify-content-center align-items-center mb-2">
                                <img src="{{ asset('assets/images/send.svg') }}" alt="" class="imageicon">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxtx">Brand Management</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxhead">{{ $totalbrand }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('priceforapproval') }}">
                    <div class="box">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="imageround4 d-flex justify-content-center align-items-center mb-2">
                                <img src="{{ asset('assets/images/ic_Projects.svg') }}" alt=""
                                    class="imageicon">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxtx">Price for Approval</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="boxhead">{{ $totalpriceapp }}</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- <div class="col-md-3">
                <div class="box">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="imageround4 d-flex justify-content-center align-items-center mb-2">
                            <img src="{{ asset('assets/images/ic_Projects.svg') }}" alt="" class="imageicon">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxtx"><a href="{{ route('storeimageforapp') }}">Storefront Image for Approval</a></span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="boxhead">{{ $storeimgapp }}</span>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="versionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Current App Version</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('appversion_submit') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">Current App Version</span>
                            <input type="text" class="fld" name="current_version"
                                placeholder="Enter Current App Version" value="{{ $appversion->current_version }}">
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
