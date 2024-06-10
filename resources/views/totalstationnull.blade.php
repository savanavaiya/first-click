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
</head>

<body>

    @include('header')

    <div class="container-fluid">
        <div class="nodatafound">
            <img src="{{ asset('assets/images/nodatafound.png') }}" alt="nodatafound" height="500px" width="500px">
            <div class="norecordtxpo">
                <span class="norecordfoundtx"><center>No Record Found</center></span>
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

</body>

</html>
