<?php


namespace App\Livewire\Admin;

use App\Http\Customs\CustomHelper;
use App\Models\Setting;
use Livewire\Component;
use App\Models\SmsTracker;

class ManageSettingsComponent extends Component
{
    public $whatsapp_link = "";
    public $whatsapp_number = "";

    public $contact_number = "";
    public bool $maintenance_mode = false;
    public string $maintenance_message = "We are undergoing maintenance. Please check back shortly.";


    protected $rules = [
        'whatsapp_link' => 'nullable|url',
        'whatsapp_number' => 'nullable|string',
        'contact_number' => 'required|string',
        'maintenance_mode' => 'boolean',
        'maintenance_message' => 'nullable|string|max:255',
    ];


    public function saveRecord(){
        $this->validate();

        $settings = Setting::first();
        if (!$settings) {
            Setting::create([
                'whatsapp_link' => $this->whatsapp_link,
                'whatsapp_number' => $this->whatsapp_number,
                'contact_number' => $this->contact_number,
                'maintenance_mode' => $this->maintenance_mode,
                'maintenance_message' => $this->maintenance_message,
            ]);
        }else {
            $settings->update([
                'whatsapp_link' => $this->whatsapp_link,
                'whatsapp_number' => $this->whatsapp_number,
                'contact_number' => $this->contact_number,
                'maintenance_mode' => $this->maintenance_mode,
                'maintenance_message' => $this->maintenance_message,
            ]);
        }

        CustomHelper::message("success", "Contact Information updated successfully.");


    }

    public function render()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->whatsapp_link = $setting->whatsapp_link;
            $this->whatsapp_number = $setting->whatsapp_number;
            $this->contact_number = $setting->contact_number;
            $this->maintenance_mode = (bool) $setting->maintenance_mode;
            $this->maintenance_message = (string) ($setting->maintenance_message ?: "We are undergoing maintenance. Please check back shortly.");


        } else {
            $this->whatsapp_link = "";
            $this->whatsapp_number = "";
            $this->contact_number = "";
            $this->maintenance_mode = false;
            $this->maintenance_message = "We are undergoing maintenance. Please check back shortly.";
        }
        return view('livewire.admin.manage-settings-component', [
            'whatsapp_link' => $this->whatsapp_link,
            'whatsapp_number' => $this->whatsapp_number,
            'contact_number' => $this->contact_number,
            'maintenance_mode' => $this->maintenance_mode,
            'maintenance_message' => $this->maintenance_message,
        ]);
    }
}
