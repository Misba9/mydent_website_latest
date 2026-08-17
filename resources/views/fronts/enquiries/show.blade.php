@extends('layouts.app')
@section('title')
    Enquiry Details
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Enquiry Details</h1>
            <a href="{{ route('enquiries.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">Full Name:</label>
                        <p class="fs-5 text-gray-900">{{ $enquiry->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">Email Address:</label>
                        <p class="fs-5 text-gray-900">{{ $enquiry->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">Phone Number:</label>
                        <p class="fs-5 text-gray-900">{{ $enquiry->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">Submitted On:</label>
                        <p class="fs-5 text-gray-900">{{ $enquiry->created_at ? $enquiry->created_at->format('F d, Y H:i') : 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold text-gray-700">Subject:</label>
                        <p class="fs-5 text-gray-900">{{ $enquiry->subject ?? 'General Enquiry' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold text-gray-700">Message Content:</label>
                        <div class="p-4 bg-light rounded text-gray-800 fs-5 lh-lg">
                            {!! nl2br(e($enquiry->message)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
