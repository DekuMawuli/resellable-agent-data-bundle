<?php

namespace App\Livewire\User;

use App\Http\Customs\CustomHelper;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileDetailsComponent extends Component
{

    public $name = "";
    public $phone = "";


    public $password = "";
    public $password_confirmation = "";

    public $current_password = "";


    public function updateProfile(){
        $user = auth()->user();
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:10', Rule::unique('users', 'phone')->ignore($user->id)],
        ]);

        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->save();

        CustomHelper::message("info", "Profile updated successfully.");

    }


    public function changePassword(){
        $this->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            CustomHelper::message("warning", "Current password does not match.");
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        CustomHelper::message("info", "Password changed successfully.");

        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.user.profile-details-component');
    }



    public function mount(){
        $user = auth()->user();
        $this->name = $user->name;
        $this->phone = $user->phone;
    }
}
