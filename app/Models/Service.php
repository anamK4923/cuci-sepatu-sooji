<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan User (jika ada)
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    // Scope untuk filter berdasarkan status
    // public function scopeByStatus($query, $status)
    // {
    //     return $query->where('status', $status);
    // }

    // public function scopePending($query)
    // {
    //     return $query->where('status', 'pending');
    // }

    // public function scopePaid($query)
    // {
    //     return $query->where('payment_status', 'paid');
    // }
}
