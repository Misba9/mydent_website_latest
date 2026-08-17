@extends('layouts.app')
@section('title')
    Enquiries
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Contact Enquiries</h1>
        </div>
        <div class="card">
            <div class="card-body">
                @if(isset($enquiries) && count($enquiries) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enquiries as $enquiry)
                                    <tr>
                                        <td class="fw-bold">{{ $enquiry->name }}</td>
                                        <td>{{ $enquiry->email }}</td>
                                        <td>{{ $enquiry->phone }}</td>
                                        <td>{{ Str::limit($enquiry->subject ?? 'N/A', 25) }}</td>
                                        <td>{{ Str::limit($enquiry->message, 40) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $enquiry->status == 1 ? 'success' : 'warning' }}">
                                                {{ $enquiry->status == 1 ? 'Read' : 'Unread' }}
                                            </span>
                                        </td>
                                        <td>{{ $enquiry->created_at ? $enquiry->created_at->format('Y-m-d H:i') : '' }}</td>
                                        <td>
                                            <a href="{{ route('enquiries.show', $enquiry->id) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-circle" title="View Enquiry">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">No enquiries found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
