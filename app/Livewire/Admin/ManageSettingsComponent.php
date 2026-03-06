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


    protected $rules = [
        'whatsapp_link' => 'nullable|url',
        'whatsapp_number' => 'nullable|string',
        'contact_number' => 'required|string',
    ];


    public function saveRecord(){
        $this->validate();

        $settings = Setting::first();
        if (!$settings) {
            Setting::create([
                'whatsapp_link' => $this->whatsapp_link,
                'whatsapp_number' => $this->whatsapp_number,
                'contact_number' => $this->contact_number,
            ]);
        }else {
            $settings->update([
                'whatsapp_link' => $this->whatsapp_link,
                'whatsapp_number' => $this->whatsapp_number,
                'contact_number' => $this->contact_number,
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


        } else {
            $this->whatsapp_link = "";
            $this->whatsapp_number = "";
            $this->contact_number = "";
        }
        return view('livewire.admin.manage-settings-component', [
            'whatsapp_link' => $this->whatsapp_link,
            'whatsapp_number' => $this->whatsapp_number,
            'contact_number' => $this->contact_number,
        ]);
    }
}
