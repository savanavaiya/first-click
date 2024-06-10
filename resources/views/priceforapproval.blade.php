<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Price For Approval</title>
    <link rel="stylesheet" href="{{ asset('assets/css/priceforapproval.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
</head>

<body>

    @include('header')

    {{-- <div class="container-fluid mt-3 mb-1">
        <span class="headtx">Price For Approval</span>
    </div> --}}
    <div class="d-flex justify-content-center alert-box">
        @if (session()->has('SUCCESS'))
            <span class="successtx my-2">{{ session()->get('SUCCESS') }}</span>
        @endif
        @if (session()->has('ERROR'))
            <span class="errortx my-2">{{ session()->get('ERROR') }}</span>
        @endif
    </div>

    @if (!$fulldatas->isEmpty())
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2 p-0">
                            <div class="back">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="headingleft">Price For Approval</span>
                                    </div>
                                </div>

                                @foreach ($fulldatas as $fulldata)
                                    <div class="row mt-2 brandbox" data-id="{{ $fulldata->id }}"
                                        onclick="priceforappbox(this)">
                                        <div class="col-md-3 p-0 d-flex justify-content-center align-items-center">
                                            <div class="brand_logo">
                                                @if ($fulldata->storedata->brand_logo == null)
                                                    <img src="{{ asset('assets/images/brandlogostatic.svg') }}"
                                                        alt="brand_logo" class="brand_logoimg">
                                                @else
                                                    <img src="{{ asset('/public/images/brandlogo/' . $fulldata->storedata->brand_logo) }}"
                                                        alt="brand_logo" class="brand_logoimg">
                                                    {{-- <img src="{{ asset('images/brandlogo/' . $fulldata->storedata->brand_logo) }}" alt="brand_logo"
                                                    class="brand_logoimg"> --}}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-9 p-0">
                                            <div>
                                                <span class="brandhead">{{ $fulldata->storedata->store_name }}</span>
                                            </div>
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/images/location.svg') }}" alt="">
                                                <span class="brandtx">{{ $fulldata->storedata->store_address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-10 backright" id="screen1">
                            <div class="display-data">

                            </div>

                        </div>
                    </div>
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        setTimeout(function() {
            $('.alert-box').remove();
        }, 3000);
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
        $(document).ready(function() {
            var id = {{ isset($fulldatas['0']) ? $fulldatas['0']->id : 0 }};
            // var updid = localStorage.getItem("afterupdateid");
            console.log(id);

            if (localStorage.getItem("afterupdateid") != null) {
                id = localStorage.getItem("afterupdateid");
            }

            $.ajax({
                type: "GET",
                url: "{{ route('priceforapppageright') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                success: function(data) {
                    $('.display-data').html(data.data);
                }
            });
        })

        function priceforappbox(data) {
            var id = $(data).attr('data-id');
            // alert(id);
            console.log(id);
            $.ajax({
                type: "GET",
                url: "{{ route('priceforapppageright') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                success: function(data) {
                    $('.display-data').html(data.data);
                }
            });
        }
    </script>

    <script type="text/javascript">
        // add row
        $(document).on('click', '#addRowedit', function() {
            var html3 = '';
            html3 += '<div id="inputFormRowedit">';
            html3 += '<div class="input-group mb-3 row">';
            html3 += '<div class="col-md-5">';
            html3 +=
                '<input type="text" class="fldforproducttitle" name="gasolinet[]" id="field-name" placeholder="Title">';
            html3 += '</div>';
            html3 += '<div class="col-md-5">';
            html3 +=
                '<input type="text" class="fldforproductvalue" name="gasolinep[]" id="field-value" placeholder="Price">';
            html3 += '</div>';
            html3 += '<div class="col-md-2 d-flex justify-content-center align-items-center">';
            html3 +=
                '<img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon" class="cusicon" id="removeRowedit">';
            html3 += '</div>';
            html3 += '</div>';
            html3 += '</div>';
            $(this).prev('#newRowedit').append(html3);
        });

        // remove row
        $(document).on('click', '#removeRowedit', function() {
            $(this).closest('#inputFormRowedit').remove();
        });
    </script>
    <script type="text/javascript">
        // add row
        $(document).on('click', '#addRowedit2', function() {
            var html4 = '';
            html4 += '<div id="inputFormRowedit2">';
            html4 += '<div class="input-group mb-3 row">';
            html4 += '<div class="col-md-5">';
            html4 +=
                '<input type="text" class="fldforproducttitle"name="dieselt[]" id="field-name" placeholder="Title">';
            html4 += '</div>';
            html4 += '<div class="col-md-5">';
            html4 +=
                '<input type="text" class="fldforproductvalue2" name="dieselp[]" id="field-value" placeholder="Price">';
            html4 += '</div>';
            html4 += '<div class="col-md-2 d-flex justify-content-center align-items-center">';
            html4 +=
                '<img src="{{ asset('assets/images/minusicon.svg') }}" alt="remove_icon" class="cusicon" id="removeRowedit2">';
            html4 += '</div>';
            html4 += '</div>';
            html4 += '</div>';
            $(this).prev('#newRowedit2').append(html4);
        });

        // remove row
        $(document).on('click', '#removeRowedit2', function() {
            $(this).closest('#inputFormRowedit2').remove();
        });
    </script>

</body>

</html>
