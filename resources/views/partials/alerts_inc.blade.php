@if(session()->has("at"))
    @php
        $alertType = session()->get("at");
        $icon = match ($alertType) {
            "success" => "fa-check-circle",
            "danger" => "fa-exclamation-circle",
            "warning" => "fa-exclamation-triangle",
            "info" => "fa-info-circle",
            "primary" => "fa-bell",
            default => "fa-bell",
        };
    @endphp

    <div class="alert alert-{{ $alertType }} alert-dismissible fade show d-flex align-items-start gap-2 shadow-sm border-0 mb-3" role="alert">
        <i class="fas {{ $icon }} font-size-18 mt-1" aria-hidden="true"></i>
        <div class="flex-grow-1">
            {{ session()->get("am") }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
