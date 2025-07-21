@extends('admin.layout')

@section('title','Tournament List')

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mt-3 mb-4">Tournaments Listing</h2>

    <div class="card">
        <div class="card-body">
            <table id="tournaments-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tournament Name</th>
                        <th>Match Type</th>
                        <th>User Name</th>
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
@endsection

@section('page-script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    let table = $('#tournaments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.tournamentslist') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'tournament_name', name: 'tournament_name' },
            { data: 'match_type', name: 'match_type' },
            { data: 'user_name', name: 'user_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Delete function
    $(document).on('click', '.delete-tournament', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the tournament permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('admin.tournamentsdelete') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id
                }, function (data) {
                    if (data.success) {
                        table.ajax.reload();
                        Swal.fire('Deleted!', data.message, 'success');
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
