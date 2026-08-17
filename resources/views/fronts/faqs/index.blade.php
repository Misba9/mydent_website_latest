@extends('layouts.app')
@section('title')
    FAQs
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">FAQs Management</h1>
            <a href="{{ route('faqs.create') }}" class="btn btn-primary">Add FAQ</a>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                @if(isset($faqs) && count($faqs) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faqs as $faq)
                                    <tr>
                                        <td class="fw-bold">{{ $faq->question }}</td>
                                        <td>{{ Str::limit($faq->answer, 60) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('faqs.edit', $faq->id) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-1" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="javascript:void(0)" data-id="{{ $faq->id }}" class="btn btn-sm btn-icon btn-outline-danger rounded-circle deleteFaqBtn" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">No FAQs found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
