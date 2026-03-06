@if(session()->has("at"))
    <div class="alert alert-{{ session()->get("at") }} alert-icon mb-4">
        <i class="fas fa-exclamation-circle"></i>
        {!! session()->get("am") !!}
        <button type="button" class="btn btn-link btn-close">
        <i class="d-icon-times"></i>
        </button>
    </div>
@endif
