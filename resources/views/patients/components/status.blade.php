<label class="form-check form-switch form-switch-sm d-flex justify-content-center">
    <input type="checkbox" name="status" class="form-check-input patient-status" data-id="{{ $row->id }}" value="{{ $row->user->status }}" {{ $row->user->status == 1 ? 'checked' : '' }}>
    <span class="custom-switch-indicator"></span>
</label>
