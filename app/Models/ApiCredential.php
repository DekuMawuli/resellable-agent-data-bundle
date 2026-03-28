<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiCredential extends Model
{
    protected $fillable = [
        'key_name',
        'key_label',
        'key_group',
        'is_secret',
        'value',
        'updated_by',
    ];

    protected $casts = [
        'value'     => 'encrypted',   // AES-256-CBC via APP_KEY at rest
        'is_secret' => 'boolean',
    ];

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Returns a masked representation safe to display in the UI.
     * e.g. sk_live_****AbCd  or  https://****om
     */
    public function maskedValue(): ?string
    {
        if (!filled($this->value)) {
            return null;
        }

        $v   = (string) $this->value;
        $len = mb_strlen($v);

        if ($len <= 8) {
            return str_repeat('*', $len);
        }

        $prefix  = mb_substr($v, 0, 4);
        $suffix  = mb_substr($v, -4);
        $stars   = str_repeat('*', max(4, $len - 8));

        return "{$prefix}{$stars}{$suffix}";
    }

    /**
     * True if a value has been stored in the database.
     */
    public function hasValue(): bool
    {
        return filled($this->value);
    }
}
