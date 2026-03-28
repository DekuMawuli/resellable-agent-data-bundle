<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'provider_reference',
        'payment_made',
        'status',
        'provider_status',
        'customer_id',
        'product_id',
        'total_amount',
        'phone_number',
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
            'payment_made' => 'boolean',
            'customer_id' => 'integer',
            'product_id' => 'integer',
            'total_amount' => 'decimal:2',
        ];
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
