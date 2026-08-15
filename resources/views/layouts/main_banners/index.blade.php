@extends('layouts.app')
@section('title')
    Main Banners
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Main Banners</h1>
            <a href="{{ route('main-banners.create') }}" class="btn btn-primary">Add Banner</a>
        </div>
        <div class="card">
            <div class="card-body">
                @if(isset($mainBanners) && $mainBanners->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Page</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mainBanners as $banner)
                                    <tr>
                                        <td>{{ $banner->page }}</td>
                                        <td>{{ $banner->title ?? 'N/A' }}</td>
                                        <td>
                                            @if($banner->image)
                                                <img src="{{ asset($banner->image) }}" width="100" class="img-thumbnail" alt="banner">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('main-banners.edit', $banner->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('main-banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No main banners found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
