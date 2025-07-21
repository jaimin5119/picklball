@extends('admin.layout')

{{-- Page Title --}}
@section('title','Dashboard')

{{-- Vendor Style --}}
@section('vendor-style')
<style>
    .dashboard-card {
        transition: 0.3s;
        cursor: pointer;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: #333;
        border-radius: 12px;
    }

    .dashboard-card:hover {
        transform: scale(1.03);
    }

    .dashboard-icon {
        font-size: 36px;
        color: #f4792f;
    }

    .dashboard-value {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    .bg-white {
        background-color: #ffffff !important;
    }

    .dashboard-title {
        margin-top: 10px;
        font-size: 18px;
    }
</style>
@endsection

{{-- Content --}}
@section('content')

<div class="container mt-4">
    <div class="row g-4">
        <!-- Total Users -->
        <div class="col-md-4">
            <div class="card dashboard-card bg-white text-center p-3">
                <i class="fa fa-users dashboard-icon"></i>
                <h5 class="dashboard-title">Total Users</h5>
                <p class="dashboard-value">
                    Active: 120 | Inactive: 30
                </p>
            </div>
        </div>

        <!-- Total Teams -->
        <div class="col-md-4">
            <div class="card dashboard-card bg-white text-center p-3">
                <i class="fa fa-users-cog dashboard-icon"></i>
                <h5 class="dashboard-title">Total Teams</h5>
                <p class="dashboard-value">18</p>
            </div>
        </div>

        <!-- Total Matches Played -->
        <div class="col-md-4">
            <div class="card dashboard-card bg-white text-center p-3">
                <i class="fa fa-futbol dashboard-icon"></i>
                <h5 class="dashboard-title">Matches Played</h5>
                <p class="dashboard-value">47</p>
            </div>
        </div>

        <!-- Total Tournaments -->
        <div class="col-md-4">
            <div class="card dashboard-card bg-white text-center p-3">
                <i class="fa fa-trophy dashboard-icon"></i>
                <h5 class="dashboard-title">Tournaments</h5>
                <p class="dashboard-value">5</p>
            </div>
        </div>

        <!-- Today's Active Matches -->
        <div class="col-md-4">
            <div class="card dashboard-card bg-white text-center p-3">
                <i class="fa fa-calendar-day dashboard-icon"></i>
                <h5 class="dashboard-title">Today's Active Matches</h5>
                <p class="dashboard-value">3</p>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Page Script --}}
@section('page-script')
<!-- No additional JS needed -->
@endsection
