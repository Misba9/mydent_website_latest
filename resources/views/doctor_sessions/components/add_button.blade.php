<div class="card-toolbar ms-auto">
    <a href="{{ isRole('doctor') ? route('doctors.doctor.schedule.edit') : route('doctor-sessions.create') }}" 
       class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> {{ __('messages.doctor_session.add_doctor_session') }}
    </a>
</div>
