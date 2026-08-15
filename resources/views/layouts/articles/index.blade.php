@extends('layouts.app')
@section('title')
    Articles
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Articles</h1>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Add Article</a>
        </div>
        <div class="card">
            <div class="card-body">
                @if(isset($articles) && $articles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>
                                            @if($article->thumbnail)
                                                <img src="{{ asset($article->thumbnail) }}" width="80" class="img-thumbnail" alt="thumbnail">
                                            @endif
                                        </td>
                                        <td>{{ $article->title }}</td>
                                        <td>{{ $article->created_at ? $article->created_at->format('Y-m-d H:i') : '' }}</td>
                                        <td>
                                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline">
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
                        <p class="text-muted mb-0">No articles found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
