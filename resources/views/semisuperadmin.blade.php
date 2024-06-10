<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/css/contributor.css') }}">
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
                <span class="headtx">Admin</span>
            </div>
            <div class="col-md-4">
                <div class="row m-0">
                    <div class="col-md-8 d-flex justify-content-end align-items-center p-0">

                    </div>
                    <div class="col-md-1 d-flex justify-content-center">

                    </div>
                    <div class="col-md-3 newbutt" data-bs-toggle="modal" data-bs-target="#addmodal">
                        <img src="{{ asset('assets/images/ic_Plus.svg') }}" alt="plus" class="plusimage">
                        <span class="newbutttx">New</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center alert-box">
        @error('firstname')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('lastname')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('email')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('password')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('phone')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('profile_image')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @if (session()->has('SUCCESS'))
            <span class="successtx my-2">{{ session()->get('SUCCESS') }}</span>
        @endif
    </div>

    <div class="d-flex justify-content-center">
        <div class="alert alert-danger d-none px-5" role="alert" id="alertboot">
            You can not remove this user
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="alert alert-danger d-none px-5" role="alert" id="alertboot2">
            You can not delete your own user access
        </div>
    </div>

    <table class="w-100 mt-3">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semisuperadmins as $semisuperadmin)
                @if ($semisuperadmin->user_type == 'super_admin')
                    <tr>
                        <td>{{ $semisuperadmin->firstname }}</td>
                        <td>{{ $semisuperadmin->lastname }}</td>
                        <td>{{ $semisuperadmin->email }}</td>
                        <td>{{ $semisuperadmin->phone }}</td>
                        <td>
                            <div id="notremove">
                                <span class="droptxtred2 curpoi"><img src="{{ asset('assets/images/remove.svg') }}"
                                        alt="remove" class="dropdownimg2"></span>
                            </div>
                        </td>
                    </tr>

                @elseif($semisuperadmin->id == auth()->user()->id)
                <tr>
                    <td>{{ $semisuperadmin->firstname }}</td>
                    <td>{{ $semisuperadmin->lastname }}</td>
                    <td>{{ $semisuperadmin->email }}</td>
                    <td>{{ $semisuperadmin->phone }}</td>
                    <td>
                        <div id="notremovelogin">
                            <span class="droptxtred2 curpoi"><img src="{{ asset('assets/images/remove.svg') }}"
                                    alt="remove" class="dropdownimg2"></span>
                        </div>
                    </td>
                </tr>
                @else
                    <tr>
                        <td>{{ $semisuperadmin->firstname }}</td>
                        <td>{{ $semisuperadmin->lastname }}</td>
                        <td>{{ $semisuperadmin->email }}</td>
                        <td>{{ $semisuperadmin->phone }}</td>
                        <td>
                            <div id="remove" data-id="{{ $semisuperadmin->id }}" data-bs-toggle="modal" data-bs-target="#removemodal">
                                <span class="droptxtred2 curpoi"><img src="{{ asset('assets/images/remove.svg') }}"
                                        alt="remove" class="dropdownimg2"></span>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Add Admin</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addsemisuperadmin') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">First Name</span>
                            <input type="text" class="fld" name="firstname" placeholder="Enter First Name">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Last Name</span>
                            <input type="text" class="fld" name="lastname" placeholder="Enter Last Name">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Email</span>
                            <input type="text" class="fld" name="email" placeholder="Enter Email">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Passwrod</span>
                            <input type="password" class="fld" name="password" placeholder="Enter Password">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Phone</span>
                            <input type="text" class="fld" name="phone" placeholder="Enter Phone">
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <div class="cancelbutt" data-bs-dismiss="modal">
                                <span class="canceltx">Cancel</span>
                            </div>
                            <button class="invcontbutt" type="submit">
                                <span class="invconttx">Add</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Remove Admin</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove this admin?</span>
                        </div>
                        <form action="{{ route('removesemisuperadmin') }}" method="POST">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hide the preloader when the page is fully loaded
            window.addEventListener("load", function() {
                var preloader = document.querySelector(".preloader");
                preloader.style.display = "none";
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
        $(document).on("click", "#remove", function() {
            // alert();
            var removeid = $(this).attr('data-id');
            console.log(removeid);
            $("#removeid").val(removeid);
        });
    </script>
    <script>
        setTimeout(function() {
            $('.alert-box').remove();
        }, 3000);
    </script>
    <script>
        $(document).ready(function(){
            // $('#alertboot').hide()
            $('#notremove').click(function(){
                // $('#alertboot').show()
                $('#alertboot').removeClass('d-none');
                $('#alertboot').addClass('d-block');

                setTimeout(function() {
                    $('#alertboot').removeClass('d-block');
                    $('#alertboot').addClass('d-none');
                }, 1000);
            })
        });
    </script>
    <script>
        $(document).ready(function(){
            // $('#alertboot').hide()
            $('#notremovelogin').click(function(){
                // $('#alertboot').show()
                $('#alertboot2').removeClass('d-none');
                $('#alertboot2').addClass('d-block');

                setTimeout(function() {
                    $('#alertboot2').removeClass('d-block');
                    $('#alertboot2').addClass('d-none');
                }, 1000);
            })
        });
    </script>
</body>
</html>
