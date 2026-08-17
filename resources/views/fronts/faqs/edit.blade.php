@extends('layouts.app')
@section('title')
    Edit FAQ
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Edit FAQ</h1>
            <a href="{{ route('faqs.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                {{ Form::model($faq, ['route' => ['faqs.update', $faq->id], 'method' => 'put']) }}
                    <div class="mb-5">
                        {{ Form::label('question', 'Question:', ['class' => 'form-label required']) }}
                        {{ Form::text('question', null, ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="mb-5">
                        {{ Form::label('answer', 'Answer:', ['class' => 'form-label required']) }}
                        {{ Form::textarea('answer', null, ['class' => 'form-control', 'rows' => 4, 'required']) }}
                    </div>
                    <div class="d-flex gap-2">
                        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                        <a href="{{ route('faqs.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
