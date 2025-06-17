<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'delivery_method',
        'alamat_pickup',
        'pickup_schedule',
        'notes',
        'status',
        'payment_status',
        'total_price',
        'midtrans_order_id',
    ];

    protected $casts = [
        'pickup_schedule' => 'datetime',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_WAITING_PICKUP = 'waiting_pickup';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_IN_PROCESS = 'in_process';
    const STATUS_READY = 'ready';
    const STATUS_COMPLETED = 'done';
    const STATUS_CANCELLED = 'cancelled';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';

    // Delivery method constants
    const DELIVERY_ANTAR_JEMPUT = 'antar_jemput';
    const DELIVERY_DROP_OFF = 'drop_off';

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service for this order
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_WAITING_PICKUP => 'Menunggu Penjemputan',
            self::STATUS_PICKED_UP => 'Sudah Dijemput',
            self::STATUS_IN_PROCESS => 'Sedang Diproses',
            self::STATUS_READY => 'Siap Diambil',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return $this->payment_status === self::PAYMENT_PAID ? 'Sudah Dibayar' : 'Menunggu Pembayaran';
    }

    /**
     * Get delivery method label
     */
    public function getDeliveryMethodLabelAttribute(): string
    {
        return $this->delivery_method === self::DELIVERY_ANTAR_JEMPUT ? 'Antar Jemput' : 'Drop Off';
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if payment is pending
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }

    /**
     * Generate order number
     */
    public function getOrderNumberAttribute(): string
    {
        return 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for user orders
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for pending payments
     */
    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING);
    }

    /**
     * Scope for active orders (not completed or cancelled)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }
}
