@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">X</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif

            <div class="page-title-box">
                <div class="page-title-right" style="display: block">
                    <a href="{{route($viewPath.'.create')}}" class="btn btn-dark btn-rounded"><span class="uil uil-plus"></span> Add Client</a>
                </div>
                <h4 class="page-title">Client List</h4>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <table id="clientListTable" class="table table-bordered table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th class="text-center">Sites</th>
                                    <th class="text-center" width="13%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
<script type="text/javascript">
    dataTable = $('#clientListTable').DataTable({
        ajax: {
            processing: true,
            url: "{{route('client.getClientList')}}",
            "dataType": "JSON",
            //dataSrc: 'data'
        },
        columns: [{
                data: "full_name",
                render: (data, type, row) => {
                    return `<a href="{{URL('users/')}}/${row.id}"> ${row.full_name} </a>`;
                }
            },
            {
                data: "email",
            },
            {
                data: "mobile",
            },
            {
                data: "sites_count",
                className: "text-center",
            },
            {
                data: "",
                className: "text-center",
                render: (data, type, row) => {
                    if (row.is_favourite == 1) {
                        var FavHeart = `<i class="mdi mdi-heart" style='color: red'></i> `;
                    } else {
                        var FavHeart = `<i class="mdi mdi-heart-outline"></i> `;
                    }
                    return `<div class="col-auto">
                    <a href='javascript:void(0);' onclick="markFavourite(${row.id})" data-toggle="tooltip" data-placement="bottom" title="" class="btn btn-link text-muted btn-lg p-0" data-original-title="Favourite"> ` +
                        FavHeart + `
                    </a>
                    <a href="javascript:void(0);" onclick="deleteClient(${row.id})" data-toggle="tooltip" data-placement="bottom" title="" class="btn btn-link text-danger btn-lg p-0 pr-2 ml-2" data-original-title="Delete"> <i class="uil uil-multiply"></i></a>
                    <a href="javascript:void(0);" onclick="editClient(${row.id})" data-toggle="tooltip" data-placement="bottom" title="" class="btn btn-link text-muted btn-lg p-0 pr-2" data-original-title="Edit"> <i class="uil uil-edit-alt"></i></a>
                  </div>`;
                }
            },
            {
                data: "is_favourite",
                visible: false,
            }
        ],
        keys: !0,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });



    function markFavourite(clientId) {
        $.ajax({
            type: "POST",
            url: "{{route('client.updateFavourite')}}",
            data: {
                'client_id': clientId
            }, // serializes the form's elements.
            success: function(r) {
                console.log(r);
                if (r.success) {
                    setFlesh('success', r.message);
                } else {
                    setFlesh('error', r.message);
                    //window.location.reload();
                }
                $('#clientListTable').DataTable().ajax.reload();
            },
            error: function(request, status, error) {
                if (request.status == 422) {

                }
                alert(request.responseText);
                //console.log(request);
            }
        });
    }

    function editClient(clientId) {
        window.location.href = "{{url('client/')}}/" + clientId + "/edit";
    }

    function deleteClient(clientId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{url('client')}}/" + clientId,
                    data: {
                        'id': clientId
                    }, // serializes the form's elements.
                    success: function(r) {
                        console.log(r);
                        if (r.success) {
                            setFlesh('success', r.message);
                        } else {
                            setFlesh('error', r.message);
                            //window.location.reload();
                        }
                        $('#clientListTable').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        if (request.status == 422) {

                        }
                        alert(request.responseText);
                        //console.log(request);
                    }
                });
            }
        })
    }
</script>

@endsection