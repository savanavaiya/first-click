<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
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
                <span class="headtx">Users</span>
            </div>
            <div class="col-md-4">
                <div class="row m-0">
                    <div class="col-md-8 d-flex justify-content-end align-items-center p-0">
                        <input type="text" name="search" id="search" placeholder="Search Users"
                            class="intfield">
                    </div>
                    <div class="col-md-1 d-flex justify-content-center">
                        {{-- <img src="{{ asset('assets/images/Icon_Indicator.svg') }}" alt="short_icon"
                            class="shortingicon"> --}}
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
        @error('firstclick_name')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('firstname')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('lastname')
            <span class="errortx my-2">{{ $message }}</span>
        @enderror
        @error('city')
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



    <div class="container-fluid my-4">
        <div class="d-flex mb-3">
            <a href="{{ route('contributor','totaluser') }}">
                <div class="sortingbutt {{ request()->is('contributor/totaluser') ? 'activesort' : '' }}">
                    <span class="sortingbutttx {{ request()->is('contributor/totaluser') ? 'activesorttx' : '' }}">Total User</span>
                </div>
            </a>
            <a href="{{ route('contributor','activeuser') }}">
                <div class="sortingbutt {{ request()->is('contributor/activeuser') ? 'activesort' : '' }}">
                    <span class="sortingbutttx {{ request()->is('contributor/activeuser') ? 'activesorttx' : '' }}">Active User</span>
                </div>
            </a>
            <a href="{{ route('contributor','inactiveuser') }}">
                <div class="sortingbutt {{ request()->is('contributor/inactiveuser') ? 'activesort' : '' }}">
                    <span class="sortingbutttx {{ request()->is('contributor/inactiveuser') ? 'activesorttx' : '' }}">Inactive User</span>
                </div>
            </a>
        </div>
        <div class="display-data">
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Add User</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('createnewuser') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <span class="fldname">FirstClick Name</span>
                            <input type="text" class="fld" name="firstclick_name" placeholder="Enter FirstClick Name">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">First Name</span>
                            <input type="text" class="fld" name="firstname" placeholder="Enter First Name">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Last Name</span>
                            <input type="text" class="fld" name="lastname" placeholder="Enter Last Name">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">City</span>
                            <input type="text" class="fld" name="city" placeholder="Enter City">
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
                                <span class="invconttx">Invite User</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Reject User</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('rejectuser') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="mb-3">
                            <span class="fldname">Reason Of Rejection</span>
                            <input type="text" class="fld" name="reason" placeholder="Enter Reason">
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

    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Edit User</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <form action="{{ route('edituser') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="editid" value="">
                        <div class="mb-3">
                            <span class="fldname">FirstClick Name</span>
                            <input type="text" class="fld" id="firstclick_name" name="firstclick_name"
                                placeholder="Enter FirstClick Name" value="">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">First Name</span>
                            <input type="text" class="fld" id="firstname" name="firstname"
                                placeholder="Enter Firstname" value="">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Last Name</span>
                            <input type="text" class="fld" id="lastname" name="lastname"
                                placeholder="Enter Lastname" value="">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">City</span>
                            <input type="text" class="fld" id="city" name="city"
                                placeholder="Enter City" value="">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Email</span>
                            <input type="text" class="fld" id="email" name="email"
                                placeholder="Enter Email" value="">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Phone</span>
                            <input type="text" class="fld" id="phone" name="phone"
                                placeholder="Enter Phone" value="">
                        </div>
                        <div class="mb-3">
                            <span class="fldname">Reward Points</span>
                            <input type="text" class="fld" id="points" name="points"
                                placeholder="Enter Points" value="">
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

    <div class="modal fade" id="removemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Remove User</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove this user?</span>
                        </div>
                        <form action="{{ route('removeuser') }}" method="POST">
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

    <div class="modal fade" id="viewrejectreason" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Rejected User Details</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table>
                        <thead>
                            <tr>
                                <th>FirstClick Name</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>City</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="firstclick_nametd"></td>
                                <td id="firstnametd"></td>
                                <td id="lastnametd"></td>
                                <td id="citytd"></td>
                                <td id="emailtd"></td>
                                <td id="phonetd"></td>
                                <td id="statustd"></td>
                                <td id="reason_of_rejecttd"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="removemodalforrejected" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" id="exampleModalLabel">
                        <span class="modalhead">Remove Rejected User</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="bordcuss">
                    <div class="modal-body p-0">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('assets/images/close-circle.svg') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center mb-5">
                            <span class="alerttx">Are you sure you want to Remove this Rejected user?</span>
                        </div>
                        <form action="{{ route('removeuser') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="idremoverejected" value="">

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
        var search = '';
        var query = '';
        $(document).ready(function() {

            load_data(query);

            function load_data(query) {
                $.ajax({
                    url: "{{ route('contributor',$filter) }}?" + query,
                    method: 'GET',
                    data: {
                        search: search,
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        // alert();
                        $('.display-data').html(data.data);
                    }
                })
            }

            //Search name
            $(document).on('keyup', '#search', function() {
                search = $(this).val(); // not put "var" before this variable...
                load_data(query);
            });
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                query = 'page=' + page;
                load_data(query);
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
        $(document).on("click", "#reject", function() {
            // alert();
            var id = $(this).attr('data-id');
            console.log(id);
            $("#id").val(id);
        });
    </script>

    <script>
        $(document).on("click", "#edit", function() {
            // alert();
            var editid = $(this).attr('data-id');
            console.log(editid);
            $("#editid").val(editid);
            var firstclick_name = $(this).attr('data-firstclick_name');
            console.log(firstclick_name);
            $("#firstclick_name").val(firstclick_name);
            var firstname = $(this).attr('data-firstname');
            console.log(firstname);
            $("#firstname").val(firstname);
            var lastname = $(this).attr('data-lastname');
            console.log(lastname);
            $("#lastname").val(lastname);
            var city = $(this).attr('data-city');
            console.log(city);
            $("#city").val(city);
            var email = $(this).attr('data-email');
            console.log(email);
            $("#email").val(email);
            var phone = $(this).attr('data-phone');
            console.log(phone);
            $("#phone").val(phone);
            var points = $(this).attr('data-points');
            console.log(points);
            $("#points").val(points);
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
        $(document).on("click", "#removerejected", function() {
            // alert();
            var id = $(this).attr('data-id');
            console.log(id);
            $("#idremoverejected").val(id);
        });
    </script>
    <script>
        $(document).on("click", "#viewmoreforreject", function() {
            // alert();
            var firstclick_name = $(this).attr('data-firstclick_name');
            $('#firstclick_nametd').html(firstclick_name);

            var firstname = $(this).attr('data-firstname');
            $('#firstnametd').html(firstname);

            var lastname = $(this).attr('data-lastname');
            $('#lastnametd').html(lastname);

            var city = $(this).attr('data-city');
            $('#citytd').html(city);

            var email = $(this).attr('data-email');
            $('#emailtd').html(email);

            var phone = $(this).attr('data-phone');
            $('#phonetd').html(phone);

            var status = $(this).attr('data-status');
            $('#statustd').html(status);

            var reason_of_reject = $(this).attr('data-reason_of_reject');
            $('#reason_of_rejecttd').html(reason_of_reject);
        });
    </script>
    <script>
        setTimeout(function() {
            $('.alert-box').remove();
        }, 3000);
    </script>
</body>

</html>
