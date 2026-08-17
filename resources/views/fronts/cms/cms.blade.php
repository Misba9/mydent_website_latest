@extends('layouts.app')
@section('title')
    CMS Settings
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">CMS Management</h1>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                {{ Form::open(['route' => 'cms.update', 'files' => true]) }}
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            {{ Form::label('home_title', 'Home Title:', ['class' => 'form-label']) }}
                            {{ Form::text('home_title', $cmsData['home_title'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Home Title']) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('home_experience', 'Years of Experience:', ['class' => 'form-label']) }}
                            {{ Form::text('home_experience', $cmsData['home_experience'] ?? '', ['class' => 'form-control', 'placeholder' => 'e.g. 15+']) }}
                        </div>
                        <div class="col-12">
                            {{ Form::label('home_short_description', 'Home Short Description:', ['class' => 'form-label']) }}
                            {{ Form::textarea('home_short_description', $cmsData['home_short_description'] ?? '', ['class' => 'form-control', 'rows' => 3]) }}
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        {{ Form::submit('Save CMS Changes', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
