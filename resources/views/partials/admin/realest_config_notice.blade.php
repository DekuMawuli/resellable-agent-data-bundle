@php
    $realestKey     = \App\Services\CredentialService::get('realest_api_key',  config('services.realest.api_key'));
    $realestBaseUrl = \App\Services\CredentialService::get('realest_base_url', config('services.realest.base_url'));
    $realestMissing = !filled($realestKey) || !filled($realestBaseUrl);
@endphp

@if ($realestMissing)
<div class="alert mb-3 d-flex align-items-start gap-3 p-3"
     style="background:#fffbeb;border:1px solid #fcd34d;border-left:4px solid #f59e0b;border-radius:.5rem;">
    <div class="flex-shrink-0 mt-1" style="color:#d97706;font-size:1.1rem;">
        <i class="fas fa-plug"></i>
    </div>
    <div class="flex-grow-1 min-w-0">
        <p class="mb-1 fw-semibold" style="color:#92400e;font-size:.88rem;">
            Realest API not configured
        </p>
        <p class="mb-2 text-muted" style="font-size:.8rem;line-height:1.5;">
            The data bundle catalog and order fulfilment won't work until the
            <strong>Realest API Key</strong> and <strong>Base URL</strong> are set.
            Products can still be created manually, but the catalog guide and
            automatic order processing will be unavailable.
        </p>
        <a href="{{ route('root.credentials') }}" class="btn btn-sm btn-warning fw-semibold">
            <i class="fas fa-lock me-1"></i>Configure in API Credentials
        </a>
    </div>
    <div class="flex-shrink-0">
        <span style="font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
                     background:#fde68a;color:#92400e;border-radius:9999px;padding:.2rem .6rem;">
            Action needed
        </span>
    </div>
</div>
@endif
