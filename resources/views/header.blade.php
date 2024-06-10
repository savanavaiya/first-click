<div class="preloader">
    <div class="bounce"></div>
</div>

<div class="container-fluid backcol sticky">
    <div class="d-flex justify-content-between align-items-center">
        <div class="">
            <a href="{{ route('index') }}" style="text-decoration: none">
                <img src="{{ asset('assets/images/headerlogo.svg') }}" alt="headerlogoimg" class="headerlogoimg">
            </a>
        </div>

        <div class="d-flex justify-content-center align-items-center dropdown curpoi">
            <div class="marrightheader">
                @if ($userdata->profile_image == null)
                    <img src="{{ asset('assets/images/profileimgcustom.svg') }}" alt="profileimgcustom" class="profileimgcustom">
                @else
                <img src="{{ asset('assets/images/'.$userdata->profile_image) }}" alt="profileimgcustom" class="profileimgcustom">
                @endif
            </div>
            <div class="margrightx">
                <div>
                    <span class="username">{{ $userdata->firstname }}  {{ $userdata->lastname }}</span>
                </div>
                <div>
                    {{-- <span class="admintx">{{ $userdata->user_type }}</span> --}}
                </div>
            </div>
            <div class="margrighdropicon">
                <img src="{{ asset('assets/images/ic_Dropdown.svg') }}" alt="dropdown" class="dropdowniconheader">
            </div>


            <div class="dropdown-content">
                <div class="dropbox d-block">
                    <div class="profilebox mb-2" data-bs-toggle="modal" data-bs-target="#updateuserprofile">
                        <span class="droptxt"><img src="{{ asset('assets/images/updprofile.png') }}" alt="profile" height="22px" width="22px" style="margin-right: 3px">Profile</span>
                    </div>
                    {{-- @if ($userdata->user_type == 'super_admin')
                        <div class="profilebox mb-2">
                            <a href="{{ route('semisuperadmin') }}" style="text-decoration: none!important;">
                                <span class="droptxt"><img src="{{ asset('assets/images/plusicon.svg') }}" alt="profile" height="20px" width="20px" style="margin-right: 5px">Admin</span>
                            </a>
                        </div>
                    @endif --}}
                    <div class="profilebox mb-2">
                        <a href="{{ route('semisuperadmin') }}" style="text-decoration: none!important;">
                            <span class="droptxt"><img src="{{ asset('assets/images/plusicon.svg') }}" alt="profile" height="20px" width="20px" style="margin-right: 5px">Admin</span>
                        </a>
                    </div>
                    <div class="logoutbox">
                        <span class="droptxt"><a href="{{ route('logout') }}"><img src="{{ asset('assets/images/logout2.png') }}" alt="logout" height="17px" width="17px" style="margin-right: 5px;margin-left: 3px">Logout</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="updateuserprofile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="exampleModalLabel">
                    <span class="modalhead">Update User Profile</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('updatesuperadminprofile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="" value="{{ $userdata->id }}">
                    <div class="mb-3">
                        <span class="fldname">First Name</span>
                        <input type="text" class="fld" name="firstname" placeholder="Enter First Name" value="{{ $userdata->firstname }}">
                    </div>
                    <div class="mb-3">
                        <span class="fldname">Last Name</span>
                        <input type="text" class="fld" name="lastname" placeholder="Enter Last Name" value="{{ $userdata->lastname }}">
                    </div>
                    {{-- <div class="mb-3">
                        <span class="fldname">Email</span>
                        <input type="text" class="fld" name="email" placeholder="Enter Email" value="{{ $userdata->email }}">
                    </div> --}}
                    {{-- <div class="mb-3">
                        <span class="fldname">Passwrod</span>
                        <input type="password" class="fld" name="password" placeholder="Enter Password">
                    </div> --}}
                    <div class="mb-3">
                        <span class="fldname">Phone</span>
                        <input type="text" class="fld" name="phone" placeholder="Enter Phone" value="{{ $userdata->phone }}">
                    </div>
                    <div class="mb-3">
                        <span class="fldname">Profile Image</span>
                        @if ($userdata->profile_image != null)
                        <div>
                            <img src="{{ asset('assets/images/'.$userdata->profile_image) }}" class="profile_image_edit" alt="profile_image" id="profile_image">
                        </div>
                        @endif
                        <input type="file" class="fld" name="profile_image">
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <div class="cancelbutt"  data-bs-dismiss="modal">
                            <span class="canceltx">Cancel</span>
                        </div>
                        <button class="invcontbutt" type="submit">
                            <span class="invconttx">Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Hide the preloader when the page is fully loaded
        window.addEventListener("load", function () {
            var preloader = document.querySelector(".preloader");
            preloader.style.display = "none";
        });
    });

    function Loader(){
        var preloader = document.querySelector(".preloader");
            preloader.style.display = "flex";
    }
    function Loaderclose(){
        var preloader = document.querySelector(".preloader");
            preloader.style.display = "none";
    }
</script>
