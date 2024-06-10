<table class="w-100 mb-5">
    <thead>
        <tr>
            <th>FirstClick Name</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>City</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Reward Points</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if ($total_row > 0)
            @foreach ($datas as $data)
                @if ($data->user_type == 'admin')
                    <tr>
                        <td>{{ $data->firstclick_name }}</td>
                        <td>{{ $data->firstname }}</td>
                        <td>{{ $data->lastname }}</td>
                        <td>{{ $data->city }}</td>
                        <td>{{ $data->email }}</td>
                        <td>{{ $data->phone }}</td>
                        <td>{{ $data->points }}</td>
                        @if ($data->status == '0')
                            <td class="d-flex">
                                <div class="pedimgcircle"><img src="{{ asset('assets/images/info-circle.svg') }}"
                                        alt="info_circle" class="pendingimg"></div> Pending
                            </td>
                            <td>
                                <div class="dropdown2">
                                    <span class="curpoi"><img src="{{ asset('assets/images/more_horiz.svg') }}" alt="more_horiz"></span>
                                    <div class="dropdown-content2">
                                        <div class="dropbox2">
                                            <span class="droptxt2"><a href="{{ route('approveuser',$data->id) }}"><img src="{{ asset('assets/images/approve.svg') }}"
                                                alt="approve" class="dropdownimg"> Approve</span></a>
                                        </div>
                                        <div class="dropbox2" id="reject" data-id="{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#rejectmodal">
                                            <span class="droptxt2 curpoi"><img src="{{ asset('assets/images/close-fill.svg') }}"
                                                    alt="reject" class="dropdownimg"> Reject</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @elseif($data->status == '1')
                            <td class="d-flex">
                                <div class="actimgcircle"><img src="{{ asset('assets/images/Group.svg') }}" alt="group"
                                        class="actingimg"></div> Active
                            </td>
                            <td>
                                <div class="dropdown2">
                                    <span class="curpoi"><img src="{{ asset('assets/images/more_horiz.svg') }}" alt="more_horiz"></span>
                                    <div class="dropdown-content2">
                                        <div class="dropbox2" id="edit" data-id="{{ $data->id }}" data-firstclick_name="{{ $data->firstclick_name }}" data-firstname="{{ $data->firstname }}" data-lastname="{{ $data->lastname }}" data-city="{{ $data->city }}" data-email="{{ $data->email }}" data-phone="{{ $data->phone }}" data-points="{{ $data->points }}" data-bs-toggle="modal" data-bs-target="#editmodal">
                                            <span class="droptxt2 curpoi"><img src="{{ asset('assets/images/edit-line.svg') }}"
                                                    alt="edit" class="dropdownimg2"> Edit</span>
                                        </div>
                                        <div class="dropbox2" id="remove" data-id="{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#removemodal">
                                            <span class="droptxtred2 curpoi"><img src="{{ asset('assets/images/remove.svg') }}"
                                                    alt="remove" class="dropdownimg2"> Remove</span>
                                        </div>

                                        {{-- <div class="dropbox2">
                                            <div class="d-flex">
                                                <input type="checkbox" name="need_approval" id="need_approval{{ $data->id }}" data-id="{{ $data->id }}" class="fldcheck" {{ $data->need_approval == 0 ? '' : 'checked' }}>
                                                <label for="need_approval" class="droptxt2">Need Approval</label>
                                            </div>
                                        </div> --}}

                                    </div>
                                </div>
                            </td>


                        @else
                            <td class="d-flex">
                                <img src="{{ asset('assets/images/close-circle.svg') }}" alt="reject_icon" class="reject_icon"> Rejected
                            </td>
                            <td><span data-bs-toggle="modal" data-firstclick_name="{{ $data->firstclick_name }}"
                                data-firstname="{{ $data->firstname }}"
                                data-firstname="{{ $data->firstname }}"
                                data-lastname="{{ $data->lastname }}"
                                data-city="{{ $data->city }}"
                                data-email="{{ $data->email }}"
                                data-phone="{{ $data->phone }}"
                                data-status="{{ $data->status }}"
                                data-reason_of_reject="{{ $data->reason_of_reject }}"
                                id="viewmoreforreject" data-bs-target="#viewrejectreason" class="viewbutton">View</span>

                                <span class="curpoi" style="margin-left: 15px" id="removerejected" data-id="{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#removemodalforrejected"><img src="{{ asset('assets/images/remove.svg') }}"
                                    alt="remove" class="dropdownimg2"></span>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="8"><h5><center>No Record Found</center></h5></td>
            </tr>
        @endif
    </tbody>
</table>
<div class="mt-2">
    {{$datas->links()}}
</div>
{{-- @foreach ($datas as $data)
<script>
    $("#need_approval{{ $data->id }}").change(function() {
        if(this.checked) {
            // $('#output').html('Checkbox is checked');
            // alert('Checkbox is checked');
            // var id = {{ isset($data->id) ? $data->id : 0 }};
            // var id = $('#need_approval_id').val();
            var id = $(this).attr('data-id');
            // console.log(id);
            $.ajax({
                type: "POST",
                url: "{{ route('need_approval_change') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "need_approval": 1,
                },
                success: function(data){
                    if(data == true){
                        location.reload();
                    }
                    if(data == false){
                        swal({
                            title: "Something Went Wrong!",
                            icon: "error",
                        }).then(function() {
                            location.reload();
                        });
                    }
                }
            });
        }
        else{
            // $('#output').html('Checkbox is not checked');
            // alert('Checkbox is not checked');
            // var id = {{ isset($data->id) ? $data->id : 0 }};
            // var id = $('#need_approval_id').val();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: "{{ route('need_approval_change') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "need_approval": 0,
                },
                success: function(data){
                    if(data == true){
                        location.reload();
                    }
                    if(data == false){
                        swal({
                            title: "Something Went Wrong!",
                            icon: "error",
                        }).then(function() {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
</script>
@endforeach --}}
