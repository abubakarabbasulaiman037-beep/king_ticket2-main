<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'user_id', 'payment_id', 'qr_code', 'used', 'seat_number', 'pdf_path', 'ticket_number', 'qr_path'
    ];

    protected $casts = [
        'used' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function qrUrl()
    {
        return $this->qr_code ? Storage::disk('public')->url('qrcodes/'.$this->qr_code) : null;
    }

    public function pdfUrl()
    {
        return $this->pdf_path ? Storage::disk('public')->url($this->pdf_path) : null;
    }

    public function buyerName()
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    /**
     * Generate and return QR code HTML image tag
     */
    public function generateQRCode()
    {
        if (!$this->qr_code) {
            return null;
        }

        $qrUrl = $this->qrUrl();
        if (!$qrUrl) {
            return null;
        }

        return '<img src="' . $qrUrl . '" alt="QR Code" class="w-32 h-32 object-contain" />';
    }
}
