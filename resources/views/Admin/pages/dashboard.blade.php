@extends('admin.layouts.master')
@section('content')
<!-- Main Content -->
<main class="flex-1 p-6 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Welcome to the Dashboard</h1>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- First Row: 3 Cards -->
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Employ</h5>
                        <a href="/employe" class="btn btn-primary">{{$policyCount}}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <div class="card-body">
                        <h5 class="card-title">Total User</h5>
                        <a   class="btn btn-primary">{{$user}}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Policy</h5>
                        <a href="/policy" class="btn btn-primary">{{$employe}}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row: Centered Card -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow p-3">
                    <div class="card-body">
                        <h5 class="card-title">Card 4</h5>
                        <p class="card-text">This card is centered below.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


@endsection
