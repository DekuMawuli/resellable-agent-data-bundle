<div class="cred-vault-page">
@push('styles')
<style>
/* ── Page shell (dark) ───────────────────────────────────────────────────── */
.cred-vault-page {
    background: #0f1419;
    border: 1px solid #2d333b;
    border-radius: .75rem;
    padding: 1.25rem 1.25rem 1.5rem;
    margin-bottom: 1rem;
}

/* ── Vault banner ─────────────────────────────────────────────────────────── */
.vault-banner {
    border-radius: .75rem;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.75rem;
    transition: background .3s, border-color .3s;
}
.vault-banner--locked {
    background: #1e2329;
    border: 1px solid #2d333b;
}
.vault-banner--unlocked {
    background: linear-gradient(135deg, #0a2e1a 0%, #0d3b22 100%);
    border: 1px solid #198754;
    box-shadow: 0 0 0 3px rgba(25,135,84,.15);
}
.vault-banner__icon {
    width: 48px; height: 48px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.vault-banner--locked  .vault-banner__icon { background: #2d333b; color: #8b949e; }
.vault-banner--unlocked .vault-banner__icon { background: rgba(25,135,84,.25); color: #4ade80; }
.vault-banner__title   { font-size: 1rem; font-weight: 700; margin: 0; }
.vault-banner--locked  .vault-banner__title   { color: #e6edf3; }
.vault-banner--unlocked .vault-banner__title  { color: #d1fae5; }
.vault-banner__sub { font-size: .78rem; margin: .1rem 0 0; }
.vault-banner--locked  .vault-banner__sub { color: #8b949e; }
.vault-banner--unlocked .vault-banner__sub { color: #6ee7b7; }

/* timer pill */
.vault-timer {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(74,222,128,.35);
    border-radius: 9999px;
    padding: .3rem .9rem;
    font-size: .78rem; color: #4ade80; font-weight: 600;
}
.vault-timer__dot {
    width: 7px; height: 7px; border-radius: 50%; background: #4ade80;
    animation: vaultPulse 1.4s ease-in-out infinite;
}
@keyframes vaultPulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .4; transform: scale(.7); }
}

/* ── Group cards ──────────────────────────────────────────────────────────── */
.cred-group { margin-bottom: 1.5rem; border-radius: .6rem; overflow: hidden; border: 1px solid #30363d; background: #161b22; }
.cred-group-header {
    display: flex; align-items: center; gap: .6rem;
    padding: .7rem 1rem;
    font-size: .72rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    border-bottom: 1px solid #30363d;
}
.cred-group-header .text-muted { color: #8b949e !important; }
.cred-group--paystack .cred-group-header { background: rgba(14,165,233,.12); color: #7dd3fc; }
.cred-group--external .cred-group-header { background: rgba(168,85,247,.12); color: #d8b4fe; }

.cred-group-header__dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.cred-group--paystack .cred-group-header__dot { background: #0ea5e9; }
.cred-group--external .cred-group-header__dot { background: #a855f7; }

/* ── Credential rows ──────────────────────────────────────────────────────── */
.cred-row {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: .75rem;
    padding: .9rem 1rem;
    background: #1c2128;
    border-bottom: 1px solid #30363d;
    transition: background .15s;
}
.cred-row:last-child { border-bottom: none; }
.cred-row:hover { background: #252b33; }
.cred-row:not(.cred-row--editing) {
    grid-template-columns: 1fr minmax(148px, max-content);
}

.cred-row__main { display: flex; align-items: center; gap: .9rem; min-width: 0; }
.cred-row__key-icon {
    width: 36px; height: 36px; border-radius: .4rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.cred-group--paystack .cred-row__key-icon { background: rgba(14,165,233,.18); color: #38bdf8; }
.cred-group--external .cred-row__key-icon { background: rgba(168,85,247,.18); color: #c084fc; }

.cred-row__label { font-size: .85rem; font-weight: 600; color: #e6edf3; line-height: 1.3; }
.cred-row__slug  { font-size: .68rem; color: #8b949e; font-family: 'Courier New', monospace; margin-top: .05rem; }

/* value display */
.cred-val { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
.cred-val__chip {
    font-family: 'Courier New', monospace;
    font-size: .78rem;
    background: #0d1117;
    color: #c9d1d9;
    border: 1px solid #30363d;
    border-radius: .3rem;
    padding: .2rem .6rem;
    letter-spacing: .04em;
    max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.cred-val__pill {
    font-size: .6rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; border-radius: 9999px; padding: .15rem .55rem;
}
.cred-val__pill--db  { background: rgba(34,197,94,.22); color: #4ade80; }
.cred-val__pill--env { background: rgba(234,179,8,.2); color: #fcd34d; }
.cred-val__pill--none { background: #21262d; color: #8b949e; }

.cred-val__empty { font-size: .8rem; color: #6e7681; font-style: italic; }

/* edit column */
.cred-row__edit { min-width: 220px; }
.cred-input {
    font-family: 'Courier New', monospace !important;
    font-size: .8rem !important;
    border-color: #30363d !important;
    background: #0d1117 !important;
    color: #e6edf3 !important;
}
.cred-input:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 .2rem rgba(99,102,241,.25) !important; }
.cred-input::placeholder { color: #6e7681; }

/* audit — readable on dark cards */
.cred-audit {
    text-align: right;
    min-width: 140px;
    max-width: 200px;
    justify-self: end;
    line-height: 1.35;
}
.cred-audit__label {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #8b949e;
    margin-bottom: .35rem;
}
.cred-audit__datetime {
    font-size: .8rem;
    font-weight: 600;
    color: #e6edf3;
}
.cred-audit__time {
    font-size: .75rem;
    font-weight: 500;
    color: #8b949e;
    margin-top: .1rem;
}
.cred-audit__user {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: .4rem;
    margin-top: .45rem;
    padding: .28rem .55rem;
    border-radius: .35rem;
    background: #21262d;
    border: 1px solid #30363d;
    font-size: .78rem;
    font-weight: 600;
    color: #c9d1d9;
    max-width: 100%;
}
.cred-audit__user i {
    color: #8b949e;
    font-size: .72rem;
    flex-shrink: 0;
}
.cred-audit__user span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.cred-audit--empty {
    font-size: .8rem;
    font-weight: 500;
    color: #6e7681;
}

/* ── Unlocked editing layout tweak ───────────────────────────────────────── */
.cred-row--editing {
    grid-template-columns: 1fr minmax(200px, 240px) minmax(148px, 1fr);
}
@media (max-width: 991.98px) {
    .cred-row--editing {
        grid-template-columns: 1fr;
    }
    .cred-audit {
        justify-self: start;
        text-align: left;
        max-width: none;
        padding-top: .5rem;
        border-top: 1px dashed #30363d;
        margin-top: .25rem;
    }
    .cred-audit__user {
        justify-content: flex-start;
    }
}

/* ── Save bar ─────────────────────────────────────────────────────────────── */
.cred-save-bar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
    background: rgba(34,197,94,.1); border: 1px solid rgba(74,222,128,.35);
    border-radius: .6rem; padding: .9rem 1.25rem;
    margin-top: 1.25rem;
}
.cred-save-bar__hint { font-size: .78rem; color: #86efac; }

/* ── Modals ───────────────────────────────────────────────────────────────── */
.vault-modal-backdrop {
    position: fixed; inset: 0; z-index: 1050;
    background: rgba(15,23,42,.6);
    backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center; padding: 1rem;
}
.vault-modal {
    background: #21262d; border: 1px solid #30363d; border-radius: .75rem; width: 100%; max-width: 420px;
    box-shadow: 0 24px 64px rgba(0,0,0,.45);
    overflow: hidden;
    animation: vaultModalIn .18s ease;
}
@keyframes vaultModalIn {
    from { opacity:0; transform:translateY(-10px) scale(.97); }
    to   { opacity:1; transform:none; }
}
.vault-modal__head {
    padding: 1.25rem 1.25rem .75rem;
    border-bottom: 1px solid #30363d;
    display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem;
}
.vault-modal__icon {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.vault-modal__icon--warn  { background: rgba(234,179,8,.2); color: #fbbf24; }
.vault-modal__icon--danger { background: rgba(248,113,113,.2); color: #f87171; }
.vault-modal__title { font-size: .95rem; font-weight: 700; color: #e6edf3; margin: 0; }
.vault-modal__sub   { font-size: .78rem; color: #8b949e; margin: .2rem 0 0; }
.vault-modal__body  { padding: 1rem 1.25rem; }
.vault-modal__foot  { padding: .75rem 1.25rem 1.25rem; display: flex; justify-content: flex-end; gap: .5rem; }
.vault-modal__note  { font-size: .75rem; border-radius: .4rem; padding: .6rem .8rem; display: flex; gap: .5rem; align-items: flex-start; }
.vault-modal__note--warn   { background: rgba(234,179,8,.12); border: 1px solid rgba(251,191,36,.35); color: #fcd34d; }
.vault-modal__note--danger { background: rgba(248,113,113,.12); border: 1px solid rgba(248,113,113,.35); color: #fca5a5; }

.cred-vault-page .vault-modal .form-label,
.vault-modal-backdrop .form-label { color: #c9d1d9; }
.cred-vault-page .vault-modal .form-control,
.vault-modal-backdrop .form-control {
    background: #0d1117;
    border-color: #30363d;
    color: #e6edf3;
}
.cred-vault-page .vault-modal .form-control:focus,
.vault-modal-backdrop .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 .2rem rgba(99,102,241,.2);
    background: #0d1117;
    color: #e6edf3;
}
.cred-vault-page .vault-modal .btn-close,
.vault-modal-backdrop .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: .55;
}
.cred-vault-page .vault-modal .btn-close:hover,
.vault-modal-backdrop .btn-close:hover { opacity: .85; }
</style>
@endpush

<div>

    {{-- ══ VAULT STATUS BANNER ════════════════════════════════════════════════ --}}
    <div class="vault-banner {{ $isUnlocked ? 'vault-banner--unlocked' : 'vault-banner--locked' }}">
        <div class="d-flex align-items-center gap-3">
            <div class="vault-banner__icon">
                <i class="fas {{ $isUnlocked ? 'fa-lock-open' : 'fa-lock' }}"></i>
            </div>
            <div>
                <p class="vault-banner__title">
                    {{ $isUnlocked ? 'Vault Unlocked — Editing Enabled' : 'API Credentials Vault' }}
                </p>
                <p class="vault-banner__sub mb-0">
                    @if ($isUnlocked)
                        Fill in any key below and click Save Changes. Blank fields keep their existing value.
                    @else
                        All values are AES-256 encrypted at rest. Password confirmation required to edit.
                    @endif
                </p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if ($isUnlocked)
                @if ($secondsRemaining !== null)
                    <div class="vault-timer">
                        <span class="vault-timer__dot"></span>
                        Locks in <span id="credTimerDisplay">{{ gmdate('i:s', max(0, $secondsRemaining)) }}</span>
                    </div>
                @endif
                <button type="button" class="btn btn-sm btn-outline-light"
                    wire:click="lock" wire:loading.attr="disabled">
                    <i class="fas fa-lock me-1"></i>Lock Now
                </button>
            @else
                <button type="button" class="btn btn-warning btn-sm fw-semibold px-3"
                    wire:click="openUnlockModal" wire:loading.attr="disabled">
                    <i class="fas fa-unlock-alt me-2"></i>Unlock to Edit
                </button>
            @endif
        </div>
    </div>

    @include('partials.alerts_inc')

    @error('general')
        <div class="alert alert-danger alert-sm py-2 small mb-3">
            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror

    {{-- ══ CREDENTIAL GROUPS ══════════════════════════════════════════════════ --}}
    <form wire:submit.prevent="requestSave">
        @foreach ($groups as $groupKey => $group)
            <div class="cred-group cred-group--{{ $groupKey }}">

                <div class="cred-group-header">
                    <span class="cred-group-header__dot"></span>
                    <i class="fas {{ $group['icon'] }}"></i>
                    {{ $group['label'] }}
                    <span class="ms-auto text-muted fw-normal" style="letter-spacing:0;text-transform:none;font-size:.71rem;">
                        {{ count($group['keys']) }} {{ Str::plural('key', count($group['keys'])) }}
                    </span>
                </div>

                @foreach ($group['keys'] as $keyName)
                    @php $cred = $credentials->get($keyName); @endphp
                    <div class="cred-row {{ $isUnlocked ? 'cred-row--editing' : '' }}">

                        {{-- Label + current value --}}
                        <div class="cred-row__main">
                            <div class="cred-row__key-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="cred-row__label">
                                    {{ $cred?->key_label ?? \App\Services\CredentialService::definedKeys()[$keyName]['label'] ?? $keyName }}
                                </div>
                                <div class="cred-row__slug">{{ $keyName }}</div>
                                <div class="cred-val mt-2">
                                    @if ($cred && $cred->hasValue())
                                        <span class="cred-val__chip">
                                            {{ $cred->is_secret ? $cred->maskedValue() : $cred->value }}
                                        </span>
                                        <span class="cred-val__pill cred-val__pill--db">
                                            <i class="fas fa-database me-1" style="font-size:.55rem;"></i>DB
                                        </span>
                                    @else
                                        <span class="cred-val__empty">Not set in DB</span>
                                        <span class="cred-val__pill cred-val__pill--env">.env fallback</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Edit input (only when unlocked) --}}
                        @if ($isUnlocked)
                            <div class="cred-row__edit">
                                @php $isSecret = !$cred || $cred->is_secret; @endphp
                                <input
                                    type="{{ $isSecret ? 'password' : 'text' }}"
                                    class="form-control form-control-sm cred-input @error("editValues.{$keyName}") is-invalid @enderror"
                                    wire:model.defer="editValues.{{ $keyName }}"
                                    placeholder="{{ ($cred && $cred->hasValue()) ? 'Keep current (blank)' : 'Enter new value…' }}"
                                    autocomplete="{{ $isSecret ? 'new-password' : 'off' }}"
                                    spellcheck="false"
                                >
                                @error("editValues.{$keyName}")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        {{-- Audit --}}
                        <div class="cred-audit">
                            @if ($cred && $cred->updated_at && $cred->hasValue())
                                <div class="cred-audit__label">Last updated</div>
                                <div class="cred-audit__datetime">{{ $cred->updated_at->format("j M Y") }}</div>
                                <div class="cred-audit__time">{{ $cred->updated_at->format("g:i A") }}</div>
                               
                            @else
                                <span class="cred-audit--empty">—</span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- Save bar --}}
        @if ($isUnlocked)
            <div class="cred-save-bar">
                <p class="cred-save-bar__hint mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Only filled fields will be updated. You'll confirm with your password before anything saves.
                </p>
                <button type="submit" class="btn btn-success btn-sm px-4 fw-semibold"
                    wire:loading.attr="disabled" wire:target="requestSave">
                    <span wire:loading wire:target="requestSave" class="spinner-border spinner-border-sm me-1" role="status"></span>
                    <i class="fas fa-save me-1" wire:loading.remove wire:target="requestSave"></i>
                    Save Changes
                </button>
            </div>
        @endif

    </form>

</div>


{{-- ══ UNLOCK MODAL ════════════════════════════════════════════════════════ --}}
@if ($showUnlockModal)
<div class="vault-modal-backdrop" wire:click.self="closeUnlockModal">
    <div class="vault-modal" role="dialog" aria-modal="true" aria-labelledby="unlockModalTitle">
        <div class="vault-modal__head">
            <div class="d-flex align-items-center gap-3">
                <div class="vault-modal__icon vault-modal__icon--warn">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <p class="vault-modal__title" id="unlockModalTitle">Unlock Credentials Vault</p>
                    <p class="vault-modal__sub mb-0">Vault will auto-lock after 15 minutes.</p>
                </div>
            </div>
            <button type="button" class="btn-close" wire:click="closeUnlockModal" aria-label="Close"></button>
        </div>

        <div class="vault-modal__body">
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.82rem;">Your account password</label>
                <input
                    type="password"
                    class="form-control @error('unlockPassword') is-invalid @enderror"
                    wire:model.defer="unlockPassword"
                    wire:keydown.enter="unlock"
                    placeholder="Enter password to unlock…"
                    autocomplete="current-password"
                >
                @error('unlockPassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="vault-modal__note vault-modal__note--warn">
                <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
                <span>After <strong>5 failed attempts</strong>, the vault will be blocked for 15 minutes.</span>
            </div>
        </div>

        <div class="vault-modal__foot">
            <button type="button" class="btn btn-sm btn-light" wire:click="closeUnlockModal">Cancel</button>
            <button type="button" class="btn btn-sm btn-warning fw-semibold px-4"
                wire:click="unlock"
                wire:loading.attr="disabled" wire:target="unlock">
                <span wire:loading wire:target="unlock" class="spinner-border spinner-border-sm me-1" role="status"></span>
                <i class="fas fa-unlock-alt me-1" wire:loading.remove wire:target="unlock"></i>
                Unlock
            </button>
        </div>
    </div>
</div>
@endif


{{-- ══ SAVE CONFIRM MODAL ══════════════════════════════════════════════════ --}}
@if ($showSaveModal)
<div class="vault-modal-backdrop" wire:click.self="closeSaveModal">
    <div class="vault-modal" role="dialog" aria-modal="true" aria-labelledby="saveModalTitle">
        <div class="vault-modal__head">
            <div class="d-flex align-items-center gap-3">
                <div class="vault-modal__icon vault-modal__icon--danger">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div>
                    <p class="vault-modal__title" id="saveModalTitle">Confirm Changes</p>
                    <p class="vault-modal__sub mb-0">This will overwrite the stored credentials.</p>
                </div>
            </div>
            <button type="button" class="btn-close" wire:click="closeSaveModal" aria-label="Close"></button>
        </div>

        <div class="vault-modal__body">
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.82rem;">Re-enter your password to confirm</label>
                <input
                    type="password"
                    class="form-control @error('savePassword') is-invalid @enderror"
                    wire:model.defer="savePassword"
                    wire:keydown.enter="confirmSave"
                    placeholder="Confirm with your password…"
                    autocomplete="current-password"
                >
                @error('savePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="vault-modal__note vault-modal__note--danger">
                <i class="fas fa-lock mt-1 flex-shrink-0"></i>
                <span>Changes take effect immediately. The vault will be <strong>locked</strong> after saving.</span>
            </div>
        </div>

        <div class="vault-modal__foot">
            <button type="button" class="btn btn-sm btn-light" wire:click="closeSaveModal">Cancel</button>
            <button type="button" class="btn btn-sm btn-danger fw-semibold px-4"
                wire:click="confirmSave"
                wire:loading.attr="disabled" wire:target="confirmSave">
                <span wire:loading wire:target="confirmSave" class="spinner-border spinner-border-sm me-1" role="status"></span>
                <i class="fas fa-save me-1" wire:loading.remove wire:target="confirmSave"></i>
                Save & Lock
            </button>
        </div>
    </div>
</div>
@endif

</div>

@push('scripts')
<script>
(function () {
    const el = document.getElementById('credTimerDisplay');
    if (!el) return;
    let s = {{ $secondsRemaining ?? 0 }};
    function pad(n) { return String(n).padStart(2,'0'); }
    function tick() {
        if (s <= 0) { window.location.reload(); return; }
        s--;
        el.textContent = pad(Math.floor(s/60)) + ':' + pad(s%60);
        setTimeout(tick, 1000);
    }
    setTimeout(tick, 1000);
})();
</script>
@endpush
