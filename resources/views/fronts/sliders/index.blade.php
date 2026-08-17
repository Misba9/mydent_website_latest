@extends('layouts.app')
@section('title')
    Sliders
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Banner Sliders</h1>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                @if(isset($sliders) && count($sliders) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Short Description</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sliders as $slider)
                                    <tr>
                                        <td class="fw-bold">{{ $slider->title }}</td>
                                        <td>{{ Str::limit($slider->short_description, 50) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('banner.edit', $slider->id) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-circle" title="Edit">
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
                        <p class="text-muted mb-0">No banner sliders found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
