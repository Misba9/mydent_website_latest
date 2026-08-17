@extends('layouts.app')
@section('title')
    Prescription Details
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Prescription Details</h1>
            <a href="{{ route('prescriptions.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">Patient:</label>
                        <p class="fs-5 text-gray-900">{{ $prescription->patient->user->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">Doctor:</label>
                        <p class="fs-5 text-gray-900">{{ $prescription->doctor->user->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold text-gray-700">Advice / Notes:</label>
                        <p class="fs-5 text-gray-900">{{ $prescription->advice ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
