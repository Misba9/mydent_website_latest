<div class="d-flex align-items-center">
    <div class="image image-circle image-mini me-3">
        <a href="javascript:void(0)">
            <img src="{{ $row->doctor->user->profile_image ?? asset('assets/image/infycare-logo.png') }}" 
                 alt="{{ $row->doctor->user->full_name ?? 'Doctor' }}" 
                 class="user-img rounded-circle object-cover" 
                 width="40" height="40"
                 onerror="this.src='{{ asset('assets/image/infycare-logo.png') }}'">
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="javascript:void(0)" class="text-gray-800 text-hover-primary mb-1 fw-bold text-decoration-none">
            {{ $row->doctor->user->full_name ?? 'N/A' }}
        </a>
        <span class="text-muted fs-small">{{ $row->doctor->user->email ?? '' }}</span>
    </div>
</div>
