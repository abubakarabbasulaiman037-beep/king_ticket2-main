<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'title', 'description', 'date', 'location', 'banner', 'price', 'available_tickets', 'currency', 'account_number', 'scan_token', 'bank_code', 'bank_name', 'account_name', 'auto_payout', 'payout_balance', 'scanner_code', 'scanner_enabled', 'category_id', 'lga_id', 'time', 'bank_id'
    ];

    // Cast date to a DateTime/Carbon instance
    protected $casts = [
        'date' => 'datetime',
        'auto_payout' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function bannerUrl()
    {
        // If a stored banner exists, return the storage path with cache-busting timestamp
        if ($this->banner) {
            $ts = $this->updated_at ? strtotime($this->updated_at) : time();
            return '/storage/banners/' . $this->banner . '?v=' . $ts;
        }

        // Fallback to a seeded external placeholder so each event has a distinct visual.
        // Use a simple per-event seed based on the ID to guarantee uniqueness.
        $seed = 'event-' . ($this->id ?? uniqid());
        return "https://picsum.photos/seed/{$seed}/1200/600";
    }

    public function scanUrl()
    {
        if ($this->scan_token) {
            return url("/scan/".$this->scan_token);
        }
        return null;
    }

    /**
     * Generate a unique 12-character scanner code
     */
    public function generateScannerCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid() . time()), 0, 12));
        } while (Event::where('scanner_code', $code)->exists());
        
        $this->update(['scanner_code' => $code]);
        return $code;
    }

    /**
     * Get or generate scanner code
     */
    public function getScannerCode()
    {
        if (!$this->scanner_code) {
            return $this->generateScannerCode();
        }
        return $this->scanner_code;
    }

    /**
     * Regenerate scanner code
     */
    public function regenerateScannerCode()
    {
        return $this->generateScannerCode();
    }

    /**
     * Get cinematic background image for event
     */
    public function cinematicImage()
    {
        return (new \App\Services\CinematicImageService())->getEventImage($this);
    }

    /**
     * Get cinematic image with fallback to banner
     */
    public function getCinematicImageUrl()
    {
        // If stored banner exists, use it
        if ($this->banner) {
            return $this->bannerUrl();
        }

        // Otherwise get cinematic image dynamically
        return $this->cinematicImage();
    }

    /**
     * Clear cinematic image cache
     */
    public function clearCinematicImageCache()
    {
        (new \App\Services\CinematicImageService())->clearImageCache($this);
    }
}
