@extends('admin.layout')

@section('title','Master Demo Project')

{{-- Vendor Styles --}}
@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Users Listing</li>
        </ul>
    </div>
</div>

<section>
    <div class="container-fluid">
        <header>
            <div class="row">
                <div class="col-md-7">
                    <h2 class="h3 display">Users Listing</h2>
                </div>
            </div>
        </header>

        <div class="card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="page-length-option" class="table table-striped table-hover multiselect">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Gender</th>
                                <th>Location</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data will be loaded via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- Page Scripts --}}
@section('page-script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            var table = $('#page-length-option').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.userslist') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'gender', name: 'gender' },
                    { data: 'location', name: 'location' },
                    { 
                        data: 'image', 
                        name: 'image', 
                        orderable: false, 
                        searchable: false,
                        render: function(data) {
                            return '<img src="' + data + '" width="40" height="40" style="border-radius:50%;">';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            
        });

        $(document).on('click', '.toggle-status', function () {
    var userId = $(this).data('id');
    var currentStatus = $(this).data('status');
    var newStatus = currentStatus == 1 ? 0 : 1;

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change the user status?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{{ route('admin.toggleUserStatus') }}", {
                _token: "{{ csrf_token() }}",
                id: userId,
                status: newStatus
            }, function (response) {
                if (response.success) {
                    $('#page-length-option').DataTable().ajax.reload();
                    Swal.fire('Success!', response.message, 'success');
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            });
        }
    });
});

    </script>
@endsection
