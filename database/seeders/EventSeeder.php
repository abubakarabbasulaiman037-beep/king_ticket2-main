<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure banners directory exists in storage
        $bannersPath = storage_path('app/public/banners');
        if (!File::exists($bannersPath)) {
            File::makeDirectory($bannersPath, 0755, true);
        }

        // Use an existing image in public/images as the source banner
        $sourceImage = public_path('images/photo_5836733862778702705_x.jpg');
        if (!File::exists($sourceImage)) {
            // If the photo isn't available, create a simple placeholder image
            $placeholder = imagecreatetruecolor(1200, 600);
            $bg = imagecolorallocate($placeholder, 40, 40, 40);
            $textColor = imagecolorallocate($placeholder, 255, 255, 255);
            imagefill($placeholder, 0, 0, $bg);
            imagestring($placeholder, 5, 20, 20, 'Banner placeholder', $textColor);
            $tmpFile = sys_get_temp_dir() . '/banner_placeholder.jpg';
            imagejpeg($placeholder, $tmpFile, 85);
            imagedestroy($placeholder);
            $sourceImage = $tmpFile;
        }

        $now = Carbon::now();

        for ($i = 1; $i <= 100; $i++) {
            $type = $i % 2 === 0 ? 'Event' : 'Cinema';
            $title = sprintf('%s %03d — %s', $type, $i, ['Live Music', 'Comedy Night', 'Movie Premiere', 'Art Exhibition', 'Tech Conference', 'Food Festival', 'Theatre Show', 'Dance Gala'][array_rand(['a','b','c','d','e','f','g','h'])]);

            $bannerName = 'banner_' . $i . '_' . time() . '.jpg';
            $dest = $bannersPath . DIRECTORY_SEPARATOR . $bannerName;

            // Copy the source image to storage banners with a unique name
            if (!File::exists($dest)) {
                File::copy($sourceImage, $dest);
            }

            Event::create([
                'user_id' => 1,
                'title' => $title,
                'description' => 'This is a sample description for ' . $title . '. Enjoy an amazing experience with music, food and entertainment. Get your tickets early.',
                'date' => $now->copy()->addDays($i)->toDateTimeString(),
                'location' => 'Hall ' . (($i % 20) + 1) . ', City Center',
                'banner' => $bannerName,
                'price' => rand(1000, 10000),
                'available_tickets' => rand(50, 500),
                'currency' => 'NGN',
                'account_number' => null,
                'scan_token' => Str::random(16),
                'bank_code' => null,
                'bank_name' => null,
                'account_name' => null,
                'auto_payout' => false,
                'payout_balance' => 0,
            ]);

            // small sleep to ensure unique timestamps when creating many files
            usleep(20000);
        }
    }
}
