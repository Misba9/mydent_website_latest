@extends('layouts.app')
@section('title')
    Add Article
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Add Article</h1>
            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                {{ Form::open(['route' => 'articles.store', 'files' => true]) }}
                    <div class="mb-5">
                        {{ Form::label('title', 'Article Title:', ['class' => 'form-label required']) }}
                        {{ Form::text('title', null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter Title']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('article_category_id', 'Category:', ['class' => 'form-label required']) }}
                        {{ Form::select('article_category_id', $categories ?? [], null, ['class' => 'form-select', 'required']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('description', 'Description:', ['class' => 'form-label required']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5, 'required']) }}
                    </div>
                    <div class="d-flex gap-2">
                        {{ Form::submit('Save Article', ['class' => 'btn btn-primary']) }}
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
