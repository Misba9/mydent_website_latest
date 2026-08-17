@extends('layouts.app')
@section('title')
    Subscribers
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Newsletter Subscribers</h1>
        </div>
        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                @if(isset($subscribers) && count($subscribers) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Email Address</th>
                                    <th>Subscribed Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscribers as $subscriber)
                                    <tr>
                                        <td class="fw-bold">{{ $subscriber->email }}</td>
                                        <td>{{ $subscriber->created_at ? $subscriber->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">No subscribers found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
