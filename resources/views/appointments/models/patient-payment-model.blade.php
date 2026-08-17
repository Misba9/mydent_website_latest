<div class="modal fade" id="patientPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.appointment.payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="patientPaymentForm">
                    @csrf
                    <input type="hidden" name="appointment_id" id="patientPaymentAppointmentId">
                    <div class="mb-4">
                        <label class="form-label required">{{ __('messages.appointment.payment_method') }}</label>
                        <select name="payment_type" id="patientPaymentType" class="form-select" required>
                            <option value="1">{{ __('messages.appointment.stripe') }}</option>
                            <option value="2">{{ __('messages.appointment.razorpay') }}</option>
                            <option value="3">{{ __('messages.appointment.paypal') }}</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="btnPay">{{ __('messages.common.pay') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
