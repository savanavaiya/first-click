@foreach ($storedatas as $storedata)
    <div class="row mt-2 brandbox" data-id="{{ $storedata->id }}" onclick="brandbox(this)">
        <div class="col-md-2 p-0 d-flex justify-content-center align-items-center">
            <div class="brand_logo">
                @if ($storedata->brand_logo == null)
                    <img src="{{ asset('assets/images/brandlogostatic.svg') }}" alt="brand_logo" class="brand_logoimg">
                @else
                    <img src="{{ asset('/public/images/brandlogo/' . $storedata->brand_logo) }}" alt="brand_logo"
                        class="brand_logoimg">
                    {{-- <img src="{{ asset('images/brandlogo/' . $storedata->brand_logo) }}" alt="brand_logo"
                                            class="brand_logoimg"> --}}
                @endif
            </div>
        </div>
        <div class="col-md-10 p-0">
            <div>
                <span class="brandhead">{{ $storedata->store_name }}</span>
            </div>
            {{-- <div class="d-flex">
                <img src="{{ asset('assets/images/location.svg') }}" alt="">
                <span class="brandtx">{{ $storedata->store_address }}</span>
            </div> --}}
            <div class="d-flex">
                <img src="{{ asset('assets/images/location.svg') }}" alt="" style="margin-right: 4px">
                <span class="brandtx">{{ $storedata->landmarks ? $storedata->landmarks : 'N/A' }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <span class="brandtxlastupd updatedbytxmain{{ $storedata->id }}"></span>
                </div>
                <div>
                    @if ($storedata->status == 'Not Updated')
                        <div class="notupdbanner">
                            <span class="notupdtx">NOT UPDATED</span>
                        </div>
                    @else

                    @endif
                </div>

            </div>
        </div>
    </div>
@endforeach



<script>
    $(document).ready(function() {
        $('.menubox1').addClass('bordercusclass');
        $('.content1').addClass('activeclass');
        $('#search').removeClass('d-none');
        $('#search').addClass('d-block');

        var id = {{ isset($storedatas['0']) ? $storedatas['0']->id : 0 }};
        // var updid = localStorage.getItem("afterupdateid");
        if (localStorage.getItem("afterupdateid") != null) {
            id = localStorage.getItem("afterupdateid");
        }

        if (localStorage.getItem("newaddid") != null) {
            id = localStorage.getItem("newaddid");
        }
        console.log(id);
        // Loader();
        $.ajax({
            type: "GET",
            url: "{{ route('storepageright') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
            },
            dataType: "json",
            success: function(data) {
                // Loaderclose();
                $('.display-data').html(data.data);
                localStorage.clear();
            }
        });

        if (localStorage.getItem("forapproval") != null) {
            $('.menubox1').removeClass('bordercusclass');
            $('.content1').removeClass('activeclass');
            $('.menubox2').addClass('bordercusclass');
            $('.content2').addClass('activeclass');

            var id = {{ isset($storedatasforapps['0']) ? $storedatasforapps['0']->id : 0 }};
            // alert(id);
            console.log(id);
            // Loader();
            $.ajax({
                type: "GET",
                url: "{{ route('storepagerightforapp') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                success: function(data) {
                    // Loaderclose();
                    $('.display-data').html(data.data);
                }
            });

            localStorage.clear();
        }

    })
    $('.menubox1').on('click', function(e) {
        e.preventDefault();
        $(this).addClass('bordercusclass');
        $('.menubox2').removeClass('bordercusclass');
        $('.content1').addClass('activeclass');
        $('.content2').removeClass('activeclass');
        $('#search').removeClass('d-none');
        $('#search').addClass('d-block');

        var id = {{ isset($storedatas['0']) ? $storedatas['0']->id : 0 }};
        // var updid = localStorage.getItem("afterupdateid");
        if (localStorage.getItem("afterupdateid") != null) {
            id = localStorage.getItem("afterupdateid");
        }

        if (localStorage.getItem("newaddid") != null) {
            id = localStorage.getItem("newaddid");
        }
        console.log(id);
        // Loader();
        $.ajax({
            type: "GET",
            url: "{{ route('storepageright') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
            },
            dataType: "json",
            success: function(data) {
                // Loaderclose();
                $('.display-data').html(data.data);
                localStorage.clear();
            }
        });
    });
</script>

<script>
    @foreach ($storedatas as $storedata)
        $(document).ready(function() {
            // UTC time string
            const utc = new Date(<?php echo strtotime($storedata->custom) * 1000; ?>);
            const utcTimeString = utc.toUTCString();
            console.log(utcTimeString);

            const utcMoment = moment.utc(utcTimeString);
            console.log(utcMoment);

            // Convert UTC time to local time
            const localMoment = utcMoment.local();

            // Display the local time
            console.log("Local time: " + localMoment.format());
            $('.updatedbytxmain{{ $storedata->id }}').html(
                'Last updated by {{ $storedata->modify_name }} on ' + localMoment.format('MMM. DD, YYYY') +
                ' at ' + localMoment.format('hh:mm a'));
        })
    @endforeach
</script>


