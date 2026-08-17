<div class="modal fade" id="changeDoctorPaymentStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.appointment.change_payment_status') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changeDoctorPaymentStatusForm">
                    @csrf
                    <input type="hidden" name="appointment_id" id="doctorPaymentStatusAppointmentId">
                    <div class="mb-4">
                        <label class="form-label required">{{ __('messages.appointment.payment_status') }}</label>
                        <select name="payment_status" id="doctorPaymentStatusSelect" class="form-select" required>
                            <option value="0">{{ __('messages.appointment.pending') }}</option>
                            <option value="1">{{ __('messages.appointment.paid') }}</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="btnSaveDoctorPaymentStatus">{{ __('messages.common.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
