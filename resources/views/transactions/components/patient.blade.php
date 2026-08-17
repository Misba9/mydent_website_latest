<div class="d-flex align-items-center">
    <div class="image image-circle image-mini me-3">
        <img src="{{ $row->user->profile_image ?? asset('assets/image/infycare-logo.png') }}" 
             alt="{{ $row->user->full_name ?? 'Patient' }}" 
             class="user-img rounded-circle object-cover" width="35" height="35"
             onerror="this.src='{{ asset('assets/image/infycare-logo.png') }}'">
    </div>
    <div class="d-flex flex-column">
        <span class="text-gray-800 fw-bold">{{ $row->user->full_name ?? 'N/A' }}</span>
        <small class="text-muted fs-7">{{ $row->user->email ?? '' }}</small>
    </div>
</div>