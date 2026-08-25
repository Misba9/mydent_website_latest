@extends('layouts.app')
@section('title')
    Add Article
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Add Article</h1>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                @if ($errors->any())
                    <div class="alert alert-danger mb-5">
                        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                {{ Form::open(['route' => 'admin.articles.store', 'files' => true]) }}
                    <div class="mb-5">
                        {{ Form::label('title', 'Article Title:', ['class' => 'form-label required']) }}
                        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => true, 'placeholder' => 'Enter Title']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('content', 'Content:', ['class' => 'form-label required']) }}
                        {{ Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => 6, 'required' => true, 'placeholder' => 'Write article content here...']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('thumbnail', 'Thumbnail Image:', ['class' => 'form-label']) }}
                        {{ Form::file('thumbnail', ['class' => 'form-control', 'accept' => 'image/*']) }}
                    </div>
                    <div class="d-flex gap-2">
                        {{ Form::submit('Save Article', ['class' => 'btn btn-primary']) }}
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
