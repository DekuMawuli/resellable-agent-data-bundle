<div class="crud-shell">
    <div class="card crud-card">
        <div class="card-body">
            <h4 class="card-title">Manage Settings</h4>
            @include("partials.alerts_inc")
            <form wire:submit.prevent="saveRecord" class="row g-2">
                <div class="col-12 col-md-6">
                    <label for="site_name" class="form-label">Whatsapp Number</label>
                    <input type="text" class="form-control" wire:model='whatsapp_number' placeholder="Whatsapp Number">
                </div>
                <div class="col-12 col-md-6">
                    <label for="site_name" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" wire:model='contact_number' placeholder="Contact Number">
                    @error('contact_number')
                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="site_name" class="form-label">Whatsapp Link</label>
                    <input type="text" class="form-control" wire:model='whatsapp_link' placeholder="Whatsapp Link">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label d-block">Paystack environment</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model="use_live_payment" id="paystackLiveSwitch">
                        <label class="form-check-label" for="paystackLiveSwitch">
                            Use live Paystack keys
                        </label>
                    </div>
                    <p class="text-muted small mb-0 mt-1">
                        When off: <code>PAYSTACK_TEST_SECRET_KEY</code> / <code>PAYSTACK_TEST_PUBLIC_KEY</code>.
                        When on: <code>PAYSTACK_SECRET_KEY</code> / <code>PAYSTACK_PUBLIC_KEY</code> (live).
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label d-block">Maintenance Mode</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model="maintenance_mode" id="maintenanceModeSwitch">
                        <label class="form-check-label" for="maintenanceModeSwitch">
                            Enable system maintenance page
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Maintenance Message</label>
                    <input type="text" class="form-control" wire:model='maintenance_message' placeholder="We are undergoing maintenance. Please check back shortly.">
                    @error('maintenance_message')
                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="saveRecord"
                    >
                        <span wire:loading wire:target="saveRecord" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        <span wire:loading.remove wire:target="saveRecord">Save Changes</span>
                        <span wire:loading wire:target="saveRecord">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
