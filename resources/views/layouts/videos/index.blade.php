@extends('layouts.app')
@section('title')
    Homepage Videos
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Homepage Videos</h1>
            <a href="{{ route('homepage-videos.create') }}" class="btn btn-primary">Upload Video / Image</a>
        </div>
        <div class="card">
            <div class="card-body">
                @if(isset($videos) && $videos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Section</th>
                                    <th>Order</th>
                                    <th>Media</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        <td>{{ $video->title }}</td>
                                        <td><span class="badge bg-secondary">{{ $video->section }}</span></td>
                                        <td>{{ $video->order ?? '0' }}</td>
                                        <td>
                                            @if($video->video_path)
                                                <video width="150" controls>
                                                    <source src="{{ asset($video->video_path) }}">
                                                </video>
                                            @elseif($video->image_path)
                                                <img src="{{ asset($video->image_path) }}" width="100" class="img-thumbnail" alt="image">
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('homepage-videos.destroy', $video->id) }}" method="POST" class="d-inline">
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
                        <p class="text-muted mb-0">No homepage videos found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
