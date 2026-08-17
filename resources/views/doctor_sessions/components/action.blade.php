<div class="d-flex justify-content-center">
    <a href="{{ isRole('doctor') ? route('doctors.doctor.schedule.edit') : route('doctor-sessions.edit', $row->id) }}" 
       title="{{ __('messages.common.edit') }}" 
       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
       data-bs-toggle="tooltip">
        <i class="fa-solid fa-pen-to-square text-primary"></i>
    </a>
</div>
