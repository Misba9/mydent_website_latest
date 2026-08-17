<div class="d-flex align-items-center">
    <div class="image image-circle image-mini me-3">
        <a href="{{ route('patients.show', $row->id) }}">
            <img src="{{ $row->profile }}" alt="user" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='{{ asset('assets/image/infycare-logo.png') }}'">
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('patients.show', $row->id) }}" class="text-gray-900 fw-bold text-decoration-none fs-6 mb-1">
            {{ $row->user->full_name }}
        </a>
        <span class="text-muted fs-small">{{ $row->user->email }}</span>
    </div>
</div>
