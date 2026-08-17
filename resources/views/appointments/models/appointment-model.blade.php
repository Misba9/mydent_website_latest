<div class="modal fade" id="appointmentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.appointment.appointment_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="appointmentDetailBody">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">{{ __('messages.appointment.patient_name') }}:</label>
                        <p id="calPatientName" class="text-gray-900 mb-0">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">{{ __('messages.appointment.doctor_name') }}:</label>
                        <p id="calDoctorName" class="text-gray-900 mb-0">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">{{ __('messages.appointment.appointment_date') }}:</label>
                        <p id="calAppointmentDate" class="text-gray-900 mb-0">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold text-gray-700">{{ __('messages.appointment.status') }}:</label>
                        <p id="calAppointmentStatus" class="text-gray-900 mb-0">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
