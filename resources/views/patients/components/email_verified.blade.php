<div class="d-flex justify-content-center">
    @if($row->user->email_verified_at)
        <span class="badge bg-light-success text-success"><i class="fa-solid fa-check me-1"></i> Verified</span>
    @else
        <span class="badge bg-light-danger text-danger"><i class="fa-solid fa-xmark me-1"></i> Unverified</span>
    @endif
</div>
