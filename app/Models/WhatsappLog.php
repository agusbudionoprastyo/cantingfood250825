<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'phone_number',
        'country_code',
        'message',
        'status',
        'response',
        'error_message',
        'gateway_used',
        'metadata',
        'sent_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(FrontendOrder::class, 'order_id');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone_number', $phone);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}
