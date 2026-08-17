@extends('layouts.app')
@section('title')
    Testimonials
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Front Patient Testimonials</h1>
            <a href="{{ route('front-patient-testimonials.create') }}" class="btn btn-primary">Add Testimonial</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                @if(isset($frontPatientTestimonials) && count($frontPatientTestimonials) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Review</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($frontPatientTestimonials as $testimonial)
                                    <tr>
                                        <td class="fw-bold">{{ $testimonial->name }}</td>
                                        <td>{{ $testimonial->designation ?? 'Patient' }}</td>
                                        <td>{{ Str::limit($testimonial->short_description ?? $testimonial->description, 50) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('front-patient-testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-1" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">No testimonials found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
