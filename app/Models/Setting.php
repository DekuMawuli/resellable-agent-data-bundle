<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'whatsapp_link',
        'whatsapp_number',
        'contact_number',
        'account_balance',
        'use_live_payment',
        'maintenance_mode',
        'maintenance_message',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'account_balance' => 'decimal:2',
            'use_live_payment' => 'boolean',
            'maintenance_mode' => 'boolean',
        ];
    }
}
