<div class="form-check form-switch form-check-custom form-check-solid justify-content-center">
    <input class="form-check-input doctor-status" type="checkbox" data-id="{{ $row->id }}" {{ $row->user->status ? 'checked' : '' }}>
</div>