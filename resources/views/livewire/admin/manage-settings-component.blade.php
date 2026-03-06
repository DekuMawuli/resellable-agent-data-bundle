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
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
