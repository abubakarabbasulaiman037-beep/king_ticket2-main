<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'buyer_id', 'amount', 'reference', 'status', 'currency', 'country'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
