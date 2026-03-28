<div>
@push('styles')
<style>
    .cred-vault-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 1.25rem;
    }
    .cred-group-card { margin-bottom: 1.5rem; }
    .cred-group-title {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #6c757d;
        padding: .5rem .75rem;
        background: #f8f9fa;
        border-radius: .375rem .375rem 0 0;
        border: 1px solid #dee2e6;
        border-bottom: none;
    }
    .cred-table { margin-bottom: 0; border-radius: 0 0 .375rem .375rem; overflow: hidden; }
    .cred-table thead th { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; background: #fff; }
    .cred-table td { vertical-align: middle; }
    .cred-masked {
        font-family: 'Courier New', monospace;
        font-size: .82rem;
        color: #555;
        background: #f1f3f5;
        border-radius: .25rem;
        padding: .15rem .5rem;
        letter-spacing: .04em;
    }
    .cred-not-set { color: #adb5bd; font-style: italic; font-size: .82rem; }
    .cred-input { font-family: 'Courier New', monospace; font-size: .82rem; }
    .lock-timer {
        font-size: .75rem;
        color: #856404;
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: .375rem;
        padding: .3rem .75rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .audit-info { font-size: .72rem; color: #adb5bd; }
    .env-badge {
        font-size: .65rem;
        background: #e2e8f0;
        color: #475569;
        border-radius: 9999px;
        padding: .1rem .45rem;
        vertical-align: middle;
    }
</style>
@endpush

<div class="crud-shell">
    <div class="card crud-card">
        <div class="card-body">

            {{-- Header --}}
            <div class="cred-vault-header">
                <div>
                    <h4 class="card-title mb-0">
                        <i class="fas fa-lock me-2 text-secondary"></i>API Credentials Vault
                    </h4>
                    <p class="text-muted small mb-0 mt-1">
                        Values are AES-256 encrypted at rest. Editing requires password confirmation twice.
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if ($isUnlocked)
                        @if ($secondsRemaining !== null)
                            <span class="lock-timer" id="credLockTimer">
                                <i class="fas fa-clock"></i>
                                <span>Vault locks in <strong id="credTimerDisplay">{{ gmdate('i:s', max(0, $secondsRemaining)) }}</strong></span>
                            </span>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            wire:click="lock" wire:loading.attr="disabled">
                            <i class="fas fa-lock me-1"></i>Lock vault
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-warning"
                            wire:click="openUnlockModal" wire:loading.attr="disabled">
                            <i class="fas fa-unlock-alt me-1"></i>Unlock to Edit
                        </button>
                    @endif
                </div>
            </div>

            @include('partials.alerts_inc')

            @error('general')
                <div class="alert alert-danger py-2 small">{{ $message }}</div>
            @enderror

            {{-- Credential groups --}}
            <form wire:submit.prevent="requestSave">
                @foreach ($groups as $groupKey => $group)
                    <div class="cred-group-card">
                        <div class="cred-group-title">
                            <i class="fas {{ $group['icon'] }} me-2"></i>{{ $group['label'] }}
                        </div>
                        <div class="table-responsive border border-top-0 rounded-bottom">
                            <table class="table table-sm cred-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:30%">Key</th>
                                        <th>Current Value</th>
                                        @if ($isUnlocked)
                                            <th style="width:35%">New Value <span class="fw-normal text-muted">(leave blank to keep)</span></th>
                                        @endif
                                        <th style="width:18%">Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group['keys'] as $keyName)
                                        @php $cred = $credentials->get($keyName); @endphp
                                        <tr>
                                            {{-- Key name --}}
                                            <td>
                                                <span class="fw-medium" style="font-size:.82rem;">
                                                    {{ $cred?->key_label ?? \App\Services\CredentialService::definedKeys()[$keyName]['label'] ?? $keyName }}
                                                </span>
                                                <div>
                                                    <code style="font-size:.68rem;color:#94a3b8;">{{ $keyName }}</code>
                                                </div>
                                            </td>

                                            {{-- Current masked value --}}
                                            <td>
                                                @if ($cred && $cred->hasValue())
                                                    @if ($cred->is_secret)
                                                        <span class="cred-masked">{{ $cred->maskedValue() }}</span>
                                                        <div class="mt-1">
                                                            <span class="badge bg-success" style="font-size:.62rem;">DB</span>
                                                        </div>
                                                    @else
                                                        {{-- URL / non-secret: show full value --}}
                                                        <span style="font-size:.82rem;font-family:monospace;">{{ $cred->value }}</span>
                                                        <div class="mt-1">
                                                            <span class="badge bg-success" style="font-size:.62rem;">DB</span>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="cred-not-set">Not set in DB</span>
                                                    <span class="env-badge ms-1">.env fallback</span>
                                                @endif
                                            </td>

                                            {{-- Edit input (only when unlocked) --}}
                                            @if ($isUnlocked)
                                                <td>
                                                    @if ($cred && $cred->is_secret)
                                                        <input
                                                            type="password"
                                                            class="form-control form-control-sm cred-input @error("editValues.{$keyName}") is-invalid @enderror"
                                                            wire:model.defer="editValues.{{ $keyName }}"
                                                            placeholder="{{ $cred->hasValue() ? 'Leave blank to keep current' : 'Enter value' }}"
                                                            autocomplete="new-password"
                                                            spellcheck="false"
                                                        >
                                                    @else
                                                        <input
                                                            type="text"
                                                            class="form-control form-control-sm cred-input @error("editValues.{$keyName}") is-invalid @enderror"
                                                            wire:model.defer="editValues.{{ $keyName }}"
                                                            placeholder="{{ $cred && $cred->hasValue() ? 'Leave blank to keep current' : 'Enter value' }}"
                                                            autocomplete="off"
                                                            spellcheck="false"
                                                        >
                                                    @endif
                                                    @error("editValues.{$keyName}")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            @endif

                                            {{-- Audit info --}}
                                            <td class="audit-info">
                                                @if ($cred && $cred->updated_at)
                                                    <div>{{ $cred->updated_at->format('d M Y') }}</div>
                                                    <div>{{ $cred->updated_at->format('H:i') }}</div>
                                                    @if ($cred->editor)
                                                        <div class="mt-1">by {{ $cred->editor->name }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                @if ($isUnlocked)
                    <div class="mt-3 d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary"
                            wire:loading.attr="disabled" wire:target="requestSave">
                            <span wire:loading wire:target="requestSave" class="spinner-border spinner-border-sm me-1" role="status"></span>
                            <i class="fas fa-save me-1" wire:loading.remove wire:target="requestSave"></i>
                            Save Changes
                        </button>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-info-circle me-1"></i>You will need to confirm with your password again before changes are stored.
                        </p>
                    </div>
                @endif
            </form>

        </div>{{-- /card-body --}}
    </div>{{-- /card --}}
</div>{{-- /crud-shell --}}


{{-- ═══ UNLOCK MODAL ════════════════════════════════════════════════════════ --}}
@if ($showUnlockModal)
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);" role="dialog" aria-modal="true" aria-labelledby="unlockModalLabel">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title" id="unlockModalLabel">
                    <i class="fas fa-shield-alt me-2 text-warning"></i>Unlock Credentials Vault
                </h5>
                <button type="button" class="btn-close" wire:click="closeUnlockModal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted small mb-3">
                    Enter your account password to unlock the vault for editing.
                    The vault will automatically lock after <strong>15 minutes</strong>.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-medium">Your Password</label>
                    <input
                        type="password"
                        class="form-control @error('unlockPassword') is-invalid @enderror"
                        wire:model.defer="unlockPassword"
                        wire:keydown.enter="unlock"
                        placeholder="Enter your account password"
                        autocomplete="current-password"
                        autofocus
                    >
                    @error('unlockPassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-warning py-2 small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    After <strong>5 failed</strong> attempts, access will be blocked for 15 minutes.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" wire:click="closeUnlockModal">Cancel</button>
                <button type="button" class="btn btn-warning btn-sm"
                    wire:click="unlock"
                    wire:loading.attr="disabled" wire:target="unlock">
                    <span wire:loading wire:target="unlock" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <i class="fas fa-unlock-alt me-1" wire:loading.remove wire:target="unlock"></i>
                    Unlock
                </button>
            </div>
        </div>
    </div>
</div>
@endif


{{-- ═══ SAVE CONFIRM MODAL ══════════════════════════════════════════════════ --}}
@if ($showSaveModal)
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);" role="dialog" aria-modal="true" aria-labelledby="saveModalLabel">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title" id="saveModalLabel">
                    <i class="fas fa-exclamation-circle me-2 text-danger"></i>Confirm Credential Changes
                </h5>
                <button type="button" class="btn-close" wire:click="closeSaveModal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted small mb-3">
                    You are about to overwrite one or more API credentials.
                    This action will take effect immediately.
                    Re-enter your password to confirm.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-medium">Confirm Password</label>
                    <input
                        type="password"
                        class="form-control @error('savePassword') is-invalid @enderror"
                        wire:model.defer="savePassword"
                        wire:keydown.enter="confirmSave"
                        placeholder="Re-enter your account password"
                        autocomplete="current-password"
                        autofocus
                    >
                    @error('savePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-danger py-2 small mb-0">
                    <i class="fas fa-lock me-1"></i>
                    After saving, the vault will be <strong>locked automatically</strong>.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" wire:click="closeSaveModal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm"
                    wire:click="confirmSave"
                    wire:loading.attr="disabled" wire:target="confirmSave">
                    <span wire:loading wire:target="confirmSave" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <i class="fas fa-save me-1" wire:loading.remove wire:target="confirmSave"></i>
                    Save & Lock
                </button>
            </div>
        </div>
    </div>
</div>
@endif

</div>

@push('scripts')
<script>
(function () {
    const display = document.getElementById('credTimerDisplay');
    if (!display) return;

    let seconds = {{ $secondsRemaining ?? 0 }};

    function pad(n) { return String(n).padStart(2, '0'); }
    function tick() {
        if (seconds <= 0) {
            display.textContent = '00:00';
            // Reload to reflect locked state
            window.location.reload();
            return;
        }
        seconds--;
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        display.textContent = pad(m) + ':' + pad(s);
        setTimeout(tick, 1000);
    }
    setTimeout(tick, 1000);
})();
</script>
@endpush
