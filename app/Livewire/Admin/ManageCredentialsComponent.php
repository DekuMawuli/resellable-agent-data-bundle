<?php

namespace App\Livewire\Admin;

use App\Http\Customs\CustomHelper;
use App\Models\ApiCredential;
use App\Services\CredentialService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ManageCredentialsComponent extends Component
{
    // --- Unlock modal ---
    public bool   $showUnlockModal  = false;
    public string $unlockPassword   = '';

    // --- Save confirmation modal ---
    public bool   $showSaveModal  = false;
    public string $savePassword   = '';

    // --- Edit values (only what the admin types; blank = keep existing) ---
    /** @var array<string, string> */
    public array $editValues = [];

    // --- Session / rate-limit constants ---
    private const UNLOCK_SESSION_KEY = 'cred_vault_unlocked_at';
    private const LOCK_AFTER_SECONDS = 900;   // 15 minutes
    private const MAX_UNLOCK_ATTEMPTS = 5;
    private const ATTEMPT_DECAY_SECONDS = 900; // 15 minutes

    // ─── Unlock / lock ────────────────────────────────────────────────────────

    public function isUnlocked(): bool
    {
        $ts = session(self::UNLOCK_SESSION_KEY);
        if (!$ts) {
            return false;
        }
        return (now()->timestamp - (int) $ts) < self::LOCK_AFTER_SECONDS;
    }

    public function openUnlockModal(): void
    {
        $this->resetErrorBag('unlockPassword');
        $this->unlockPassword  = '';
        $this->showUnlockModal = true;
    }

    public function unlock(): void
    {
        $rateLimitKey = 'cred_unlock_' . auth()->id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::MAX_UNLOCK_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->addError('unlockPassword', "Too many failed attempts. Try again in {$seconds} seconds.");
            return;
        }

        if (!Hash::check($this->unlockPassword, auth()->user()->password)) {
            RateLimiter::hit($rateLimitKey, self::ATTEMPT_DECAY_SECONDS);
            $remaining = self::MAX_UNLOCK_ATTEMPTS - RateLimiter::attempts($rateLimitKey);
            $this->addError('unlockPassword', "Incorrect password. {$remaining} attempt(s) remaining.");
            $this->unlockPassword = '';
            return;
        }

        RateLimiter::clear($rateLimitKey);
        session()->put(self::UNLOCK_SESSION_KEY, now()->timestamp);

        $this->showUnlockModal = false;
        $this->unlockPassword  = '';

        // Initialise editValues with empty strings so form fields render
        foreach (array_keys(CredentialService::definedKeys()) as $keyName) {
            $this->editValues[$keyName] = '';
        }
    }

    public function lock(): void
    {
        session()->forget(self::UNLOCK_SESSION_KEY);
        $this->editValues     = [];
        $this->showUnlockModal = false;
        $this->showSaveModal   = false;
        $this->unlockPassword  = '';
        $this->savePassword    = '';
    }

    // ─── Save (two-step) ─────────────────────────────────────────────────────

    public function requestSave(): void
    {
        if (!$this->isUnlocked()) {
            $this->lock();
            $this->addError('general', 'Your session has expired. Please unlock again.');
            return;
        }

        $hasChanges = collect($this->editValues)->contains(fn ($v) => filled($v));
        if (!$hasChanges) {
            CustomHelper::message('info', 'No values were entered — nothing to save.');
            return;
        }

        $this->resetErrorBag('savePassword');
        $this->savePassword  = '';
        $this->showSaveModal = true;
    }

    public function confirmSave(): void
    {
        if (!$this->isUnlocked()) {
            $this->lock();
            $this->addError('savePassword', 'Session expired. Please unlock again.');
            return;
        }

        if (!Hash::check($this->savePassword, auth()->user()->password)) {
            $this->addError('savePassword', 'Incorrect password. Changes not saved.');
            $this->savePassword = '';
            return;
        }

        $defined = CredentialService::definedKeys();

        foreach ($this->editValues as $keyName => $newValue) {
            if (!filled($newValue)) {
                continue; // blank = no change, skip
            }

            if (!array_key_exists($keyName, $defined)) {
                continue; // never save keys we don't recognise
            }

            $meta = $defined[$keyName];

            ApiCredential::updateOrCreate(
                ['key_name' => $keyName],
                [
                    'key_label'  => $meta['label'],
                    'key_group'  => $meta['group'],
                    'is_secret'  => $meta['is_secret'],
                    'value'      => $newValue,
                    'updated_by' => auth()->id(),
                ]
            );
        }

        CredentialService::flush();

        $this->savePassword  = '';
        $this->showSaveModal = false;
        $this->editValues    = [];
        $this->lock();

        CustomHelper::message('success', 'Credentials saved and vault locked.');
        return $this->redirect(route('root.credentials'), navigate: false);
    }

    public function closeSaveModal(): void
    {
        $this->showSaveModal = false;
        $this->savePassword  = '';
    }

    public function closeUnlockModal(): void
    {
        $this->showUnlockModal = false;
        $this->unlockPassword  = '';
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        // Auto-expire the session if the 15-min window has passed
        if (session()->has(self::UNLOCK_SESSION_KEY) && !$this->isUnlocked()) {
            $this->lock();
        }

        // Ensure all slots exist in DB (creates with null value if missing)
        CredentialService::ensureSlots();

        $credentials = ApiCredential::with('editor')
            ->orderBy('key_group')
            ->orderBy('key_name')
            ->get()
            ->keyBy('key_name');

        $isUnlocked = $this->isUnlocked();

        // When just unlocked and editValues not yet initialised, set blanks
        if ($isUnlocked && empty($this->editValues)) {
            foreach (array_keys(CredentialService::definedKeys()) as $keyName) {
                $this->editValues[$keyName] = '';
            }
        }

        $groups = [
            'paystack' => [
                'label' => 'Paystack Payment',
                'icon'  => 'fa-credit-card',
                'keys'  => ['paystack_test_public', 'paystack_test_secret', 'paystack_live_public', 'paystack_live_secret'],
            ],
            'external' => [
                'label' => 'External APIs',
                'icon'  => 'fa-plug',
                'keys'  => ['realest_api_key', 'realest_base_url', 'pai_key'],
            ],
        ];

        $secondsRemaining = null;
        if ($isUnlocked) {
            $ts = (int) session(self::UNLOCK_SESSION_KEY);
            $secondsRemaining = self::LOCK_AFTER_SECONDS - (now()->timestamp - $ts);
        }

        return view('livewire.admin.manage-credentials-component', [
            'credentials'      => $credentials,
            'groups'           => $groups,
            'isUnlocked'       => $isUnlocked,
            'secondsRemaining' => $secondsRemaining,
        ]);
    }
}
