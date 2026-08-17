@extends('layouts.app')
@section('title')
    Add Testimonial
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Add Testimonial</h1>
            <a href="{{ route('front-patient-testimonials.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                {{ Form::open(['route' => 'front-patient-testimonials.store', 'files' => true]) }}
                    <div class="mb-5">
                        {{ Form::label('name', 'Name:', ['class' => 'form-label required']) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter Name']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('designation', 'Designation:', ['class' => 'form-label']) }}
                        {{ Form::text('designation', null, ['class' => 'form-control', 'placeholder' => 'Enter Designation']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('short_description', 'Short Review:', ['class' => 'form-label required']) }}
                        {{ Form::textarea('short_description', null, ['class' => 'form-control', 'rows' => 3, 'required']) }}
                    </div>
                    <div class="d-flex gap-2">
                        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                        <a href="{{ route('front-patient-testimonials.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
