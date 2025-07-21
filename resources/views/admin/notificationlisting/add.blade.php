@extends('admin.layout')

@section('title','Add Notification')

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
            <li class="breadcrumb-item active">Add Notification</li>
        </ul>
    </div>
</div>

<section>
    <div class="container-fluid">
        <header>
            <div class="row">
                <div class="col-md-7">
                    <h2 class="h3 display">Add Notification</h2>
                </div>
            </div>
        </header>

        <div class="card">
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('success') }}
                    </div>
                @endif

                <form id="notification-form" method="POST">
                    @csrf

                    {{-- Row: Title and Target Audience --}}
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title">Title <span class="text-danger"></span></label>
                            <input id="title" name="title" type="text" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="target_audience">Target Audience <span class="text-danger"></span></label>
                            <select name="target_audience" id="target_audience" class="form-control" required>
                                <option value="">-- Select Audience --</option>
                                <option value="0">All Users</option>
                                <option value="1">Active Users</option>
                            </select>
                        </div>
                    </div>
 <div class="row">
                    {{-- Message --}}
                    <div class="form-group col-md-6">
                        <label for="message">Message <span class="text-danger"></span></label>
                        <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
                    </div>
</div>
                    {{-- Schedule Fields --}}
                    <div class="form-group col-md-12 d-none" id="schedule-fields">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="schedule_date">Schedule Date</label>
                                <input type="date" name="schedule_date" class="form-control mb-2">
                            </div>

                            <div class="col-md-6">
                                <label for="schedule_time">Schedule Time</label>
                                <input type="time" name="schedule_time" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <button class="btn btn-primary mr-2" name="submit_type" value="send_now">
                                </i> Send Now
                            </button>

                            <button type="button" class="btn btn-warning" id="toggle-schedule">
                                </i> Schedule Later
                            </button>

                            <button type="submit" class="btn btn-success d-none" id="schedule-submit-btn" name="submit_type" value="schedule">
                               </i> Schedule Send
                            </button>

                            <a href="{{ route('admin.notificationsindex') }}" class="btn btn-secondary ml-2">
                                </i> Cancel
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
    document.getElementById('toggle-schedule').addEventListener('click', function () {
        document.getElementById('schedule-fields').classList.remove('d-none');
        document.getElementById('schedule-submit-btn').classList.remove('d-none');
        this.classList.add('d-none');
    });

    $('#notification-form').on('submit', function(e) {
    e.preventDefault();

    // Determine submit_type properly
    let submit_type = 'send_now';
    if ($('#schedule-submit-btn').is(':visible')) {
        submit_type = 'schedule';
    }

    // Prepare the data
    let formData = {
        _token: '{{ csrf_token() }}',
        title: $('#title').val(),
        message: $('#message').val(),
        target_audience: $('#target_audience').val(),
        schedule_date: $('input[name="schedule_date"]').val(),
        schedule_time: $('input[name="schedule_time"]').val(),
        submit_type: submit_type
    };

    $.ajax({
        url: "{{ route('admin.notificationsstore') }}",
        method: "POST",
        data: formData,
        success: function(response) {
    if (response.success) {
        // Show bootstrap success alert message
        let successHtml = `
            <div class="alert alert-success alert-dismissible fade show" id="ajax-success-alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${response.message}
            </div>`;
        $('.card-body').prepend(successHtml);

        // Hide after 2 seconds and redirect
        setTimeout(function() {
            $('#ajax-success-alert').fadeOut(500, function () {
                window.location.href = "{{ route('admin.notificationsindex') }}";
            });
        }, 2000);
    } else {
        let errorHtml = `
            <div class="alert alert-danger alert-dismissible fade show" id="ajax-error-alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${response.message}
            </div>`;
        $('.card-body').prepend(errorHtml);

        setTimeout(function() {
            $('#ajax-error-alert').fadeOut(500);
        }, 2000);
    }
},
error: function(xhr) {
    let errors = xhr.responseJSON.errors;
    let errorText = "";
    for (let key in errors) {
        errorText += `<div>${errors[key][0]}</div>`;
    }
    let errorHtml = `
        <div class="alert alert-danger alert-dismissible fade show" id="ajax-error-alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ${errorText}
        </div>`;
    $('.card-body').prepend(errorHtml);

    setTimeout(function() {
        $('#ajax-error-alert').fadeOut(500);
    }, 1000);
}

    });
});

</script>
@endsection
