<span class="badge bg-light-info text-info">
    {{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}
</span>