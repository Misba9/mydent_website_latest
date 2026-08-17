<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('doctors.edit', $row->id) }}" title="Edit Doctor" class="btn btn-icon btn-outline-primary btn-sm rounded-circle">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a href="javascript:void(0)" data-id="{{ $row->id }}" title="Delete Doctor" class="btn btn-icon btn-outline-danger btn-sm rounded-circle deleteDoctorBtn">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
