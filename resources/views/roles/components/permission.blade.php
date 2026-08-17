<div class="d-flex flex-wrap gap-1">
    @forelse($row->permissions as $permission)
        <span class="badge bg-secondary text-dark fs-small me-1 mb-1">{{ $permission->display_name ?? $permission->name }}</span>
    @empty
        <span class="text-muted fs-small">No permissions assigned</span>
    @endforelse
</div>
