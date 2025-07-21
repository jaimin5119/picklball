@extends('admin.layout')

@section('title','Scheduled Notifications')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Scheduled Notifications</li>
        </ul>
    </div>
</div>

<section>
    <div class="container-fluid">
        <header>
            <div class="row">
                <div class="col-md-7">
                    <h2 class="h3 display">Scheduled Notifications</h2>
                </div>

                <div class="col-md-5 button-gap d-flex justify-content-end">
                    <a class="btn btn-primary rounded-pill" href="{{ route('admin.notificationscreate') }}">Add Notification</a>

                </div>
            </div>
        </header>

        <div class="card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="scheduled-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Target</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                 <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data loaded via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(function () {
        $('#scheduled-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.scheduled.notifications') }}',
            columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'title', name: 'title' },
    { data: 'message', name: 'message' },
    { data: 'target_audience', name: 'target_audience' },
    { data: 'schedule_date', name: 'schedule_date' },
    { data: 'schedule_time', name: 'schedule_time' },
    { data: 'status', name: 'status' },
    { data: 'action', name: 'action', orderable: false, searchable: false } // new column
]

        });
    });

    $(document).on('click', '.delete-notification', function () {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this notification?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{{ route('admin.scheduleddelete') }}", {
                _token: "{{ csrf_token() }}",
                id: id
            }, function (response) {
                if (response.success) {
                    $('#scheduled-table').DataTable().ajax.reload();
                    Swal.fire('Deleted!', response.message, 'success');
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            });
        }
    });
});

</script>
@endsection
