<div>
@push('styles')
<style>
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
.cred-group { margin-bottom: 1.5rem; border-radius: .6rem; overflow: hidden; border: 1px solid #dee2e6; }
.cred-group-header {
    display: flex; align-items: center; gap: .6rem;
    padding: .7rem 1rem;
    font-size: .72rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    border-bottom: 1px solid #dee2e6;
}
.cred-group--paystack .cred-group-header { background: #f0f9ff; color: #0369a1; }
.cred-group--external .cred-group-header { background: #faf5ff; color: #7c3aed; }

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
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    transition: background .15s;
}
.cred-row:last-child { border-bottom: none; }
.cred-row:hover { background: #fafcff; }

.cred-row__main { display: flex; align-items: center; gap: .9rem; min-width: 0; }
.cred-row__key-icon {
    width: 36px; height: 36px; border-radius: .4rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.cred-group--paystack .cred-row__key-icon { background: #e0f2fe; color: #0284c7; }
.cred-group--external .cred-row__key-icon { background: #f3e8ff; color: #9333ea; }

.cred-row__label { font-size: .85rem; font-weight: 600; color: #1e293b; line-height: 1.3; }
.cred-row__slug  { font-size: .68rem; color: #94a3b8; font-family: 'Courier New', monospace; margin-top: .05rem; }

/* value display */
.cred-val { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
.cred-val__chip {
    font-family: 'Courier New', monospace;
    font-size: .78rem;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: .3rem;
    padding: .2rem .6rem;
    letter-spacing: .04em;
    max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.cred-val__pill {
    font-size: .6rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; border-radius: 9999px; padding: .15rem .55rem;
}
.cred-val__pill--db  { background: #dcfce7; color: #15803d; }
.cred-val__pill--env { background: #fef3c7; color: #92400e; }
.cred-val__pill--none { background: #f1f5f9; color: #94a3b8; }

.cred-val__empty { font-size: .8rem; color: #cbd5e1; font-style: italic; }

/* edit column */
.cred-row__edit { min-width: 220px; }
.cred-input {
    font-family: 'Courier New', monospace !important;
    font-size: .8rem !important;
    border-color: #c7d2fe !important;
    background: #fafbff !important;
}
.cred-input:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 .2rem rgba(99,102,241,.18) !important; }

/* audit */
.cred-audit { font-size: .68rem; color: #cbd5e1; text-align: right; min-width: 90px; line-height: 1.5; }

/* ── Unlocked editing layout tweak ───────────────────────────────────────── */
.cred-row--editing {
    grid-template-columns: 1fr 240px 90px;
}

/* ── Save bar ─────────────────────────────────────────────────────────────── */
.cred-save-bar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: .6rem; padding: .9rem 1.25rem;
    margin-top: 1.25rem;
}
.cred-save-bar__hint { font-size: .78rem; color: #166534; }

/* ── Modals ───────────────────────────────────────────────────────────────── */
.vault-modal-backdrop {
    position: fixed; inset: 0; z-index: 1050;
    background: rgba(15,23,42,.6);
    backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center; padding: 1rem;
}
.vault-modal {
    background: #fff; border-radius: .75rem; width: 100%; max-width: 420px;
    box-shadow: 0 24px 64px rgba(0,0,0,.22);
    overflow: hidden;
    animation: vaultModalIn .18s ease;
}
@keyframes vaultModalIn {
    from { opacity:0; transform:translateY(-10px) scale(.97); }
    to   { opacity:1; transform:none; }
}
.vault-modal__head {
    padding: 1.25rem 1.25rem .75rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem;
}
.vault-modal__icon {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.vault-modal__icon--warn  { background: #fef3c7; color: #d97706; }
.vault-modal__icon--danger { background: #fee2e2; color: #dc2626; }
.vault-modal__title { font-size: .95rem; font-weight: 700; color: #0f172a; margin: 0; }
.vault-modal__sub   { font-size: .78rem; color: #64748b; margin: .2rem 0 0; }
.vault-modal__body  { padding: 1rem 1.25rem; }
.vault-modal__foot  { padding: .75rem 1.25rem 1.25rem; display: flex; justify-content: flex-end; gap: .5rem; }
.vault-modal__note  { font-size: .75rem; border-radius: .4rem; padding: .6rem .8rem; display: flex; gap: .5rem; align-items: flex-start; }
.vault-modal__note--warn   { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
.vault-modal__note--danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
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
                                {{ $cred->updated_at->format('d M Y') }}<br>
                                {{ $cred->updated_at->format('H:i') }}<br>
                                @if ($cred->editor)
                                    <span style="color:#a0aec0;">{{ $cred->editor->name }}</span>
                                @endif
                            @else
                                <span style="color:#e2e8f0;">—</span>
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

</div>{{-- /outer div --}}


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
