<div class="row g-3">
    <div class="col-12">
        @include("partials.alerts_inc")
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-0">Manage your profile and security settings</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-info">{{ strtoupper(auth()->user()->role) }}</span>
                    <p class="text-muted mb-0 mt-2">Phone: {{ auth()->user()->phone }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="mb-3">Profile Details</h5>
                <form wire:submit.prevent="updateProfile" class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" class="form-control" wire:model.defer="name" placeholder="Enter your full name">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-12">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" class="form-control" wire:model.defer="phone" placeholder="Enter phone number">
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-12">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="updateProfile"
                        >
                            <span wire:loading wire:target="updateProfile" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            <span wire:loading.remove wire:target="updateProfile">Update Profile</span>
                            <span wire:loading wire:target="updateProfile">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="mb-3">Change Password</h5>
                <form wire:submit.prevent="changePassword" class="row g-3">
                    <div class="col-12">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" class="form-control" wire:model.defer="current_password" placeholder="Enter current password">
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-12">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" class="form-control" wire:model.defer="password" placeholder="Enter new password">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-12">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" class="form-control" wire:model.defer="password_confirmation" placeholder="Confirm new password">
                    </div>

                    <div class="col-12">
                        <button
                            type="submit"
                            class="btn btn-warning"
                            wire:loading.attr="disabled"
                            wire:target="changePassword"
                        >
                            <span wire:loading wire:target="changePassword" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            <span wire:loading.remove wire:target="changePassword">Change Password</span>
                            <span wire:loading wire:target="changePassword">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
