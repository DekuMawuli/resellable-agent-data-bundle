@if(session()->has("at"))
    @php
        $alertType = session()->get("at");
        $icon = match ($alertType) {
            "success" => "mdi-check-circle",
            "danger" => "mdi-alert-circle",
            "warning" => "mdi-alert",
            "info" => "mdi-information",
            default => "mdi-bell",
        };
    @endphp

    <div class="alert alert-{{ $alertType }} alert-dismissible fade show d-flex align-items-start gap-2 shadow-sm border-0 mb-3" role="alert">
        <i class="mdi {{ $icon }} font-size-18 mt-1"></i>
        <div class="flex-grow-1">
            {!! session()->get("am") !!}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
