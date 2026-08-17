<label class="form-check form-switch form-switch-sm d-flex justify-content-center">
    <input type="checkbox" name="is_active" class="form-check-input category-active" data-id="{{ $row->id }}" value="{{ $row->is_active }}" {{ $row->is_active == 1 ? 'checked' : '' }}>
    <span class="custom-switch-indicator"></span>
</label>
