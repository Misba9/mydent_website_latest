<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('patients.edit', $row->id) }}" title="Edit Patient" class="btn btn-icon btn-outline-primary btn-sm rounded-circle">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a href="javascript:void(0)" data-id="{{ $row->id }}" title="Delete Patient" class="btn btn-icon btn-outline-danger btn-sm rounded-circle deletePatientBtn">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
