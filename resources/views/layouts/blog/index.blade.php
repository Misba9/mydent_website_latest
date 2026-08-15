@extends('layouts.app')
@section('title')
    Blogs
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Blogs</h1>
            <a href="{{ route('blogs.create') }}" class="btn btn-primary">Add Blog</a>
        </div>
        <div class="card">
            <div class="card-body">
                @if(isset($blogs) && $blogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Issue Type</th>
                                    <th>Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blogs as $blog)
                                    <tr>
                                        <td>
                                            @if($blog->thumbnail)
                                                <img src="{{ asset($blog->thumbnail) }}" width="80" class="img-thumbnail" alt="thumbnail">
                                            @endif
                                        </td>
                                        <td>{{ $blog->title }}</td>
                                        <td>{{ $blog->issue_type }}</td>
                                        <td><span class="badge bg-info">{{ $blog->issue_level }}</span></td>
                                        <td>
                                            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline">
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
                        <p class="text-muted mb-0">No blogs found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
