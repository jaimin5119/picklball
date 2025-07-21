@extends('admin.layout')

@section('title','Edit Notification')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .d-none { display: none !important; }
    </style>
@endsection

@section('content')

<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.notificationsindex') }}">Notification List</a></li>
            <li class="breadcrumb-item active">Edit Notification</li>
        </ul>
    </div>
</div>

<section>
    <div class="container-fluid">
         <header>
            <div class="row">
                <div class="col-md-7">
                    <h2 class="h3 display">Edit Notification</h2>
                </div>
            </div>
        </header>

        <div class="card">
            <div class="card-body p-4">
                <form id="notification-form" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="notification_id" value="{{ $notification->id }}">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title">Title</label>
                            <input id="title" name="title" type="text" class="form-control" required value="{{ $notification->title }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="target_audience">Target Audience</label>
                            <select name="target_audience" id="target_audience" class="form-control" required>
                                <option value="">-- Select Audience --</option>
                                <option value="0" {{ $notification->target_audience == '0' ? 'selected' : '' }}>All Users</option>
                                <option value="1" {{ $notification->target_audience == '1' ? 'selected' : '' }}>Active Users</option>
                            </select>
                        </div>
                    </div>
                   <div class="row">
                    <div class="form-group col-md-6">
                        <label for="message">Message</label>
                        <textarea name="message" id="message" class="form-control" rows="4" required>{{ $notification->message }}</textarea>
                    </div>
                  </div>  

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="schedule_date">Schedule Date</label>
                            <input type="date" name="schedule_date" class="form-control" value="{{ $notification->schedule_date }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="schedule_time">Schedule Time</label>
                            <input type="time" name="schedule_time" class="form-control" value="{{ $notification->schedule_time }}">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success" id="update-submit-btn">
                                Update Notification
                            </button>
                            <a href="{{ route('admin.notificationsindex') }}" class="btn btn-secondary ml-2">
                                Cancel
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#notification-form').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            _token: '{{ csrf_token() }}',
            id: $('#notification_id').val(),
            title: $('#title').val(),
            message: $('#message').val(),
            target_audience: $('#target_audience').val(),
            schedule_date: $('input[name="schedule_date"]').val(),
            schedule_time: $('input[name="schedule_time"]').val()
        };

        $.ajax({
            url: "{{ route('admin.notificationsupdate') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = "{{ route('admin.notificationsindex') }}";
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });
</script>
@endsection
