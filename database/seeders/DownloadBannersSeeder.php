<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class DownloadBannersSeeder extends Seeder
{
    public function run(): void
    {
        $banners = Storage::disk('public')->exists('banners') ? Storage::disk('public')->files('banners') : [];
        if (!Storage::disk('public')->exists('banners')) {
            Storage::disk('public')->makeDirectory('banners');
        }

        $events = Event::all();

        foreach ($events as $e) {
            $seed = urlencode($e->id . '-' . preg_replace('/[^A-Za-z0-9]/', '', $e->title));
            $url = "https://picsum.photos/seed/{$seed}/1200/600";

            try {
                $res = Http::timeout(15)->get($url);
                if ($res->ok()) {
                    $filename = 'banner_event_' . $e->id . '_' . time() . '.jpg';
                    Storage::disk('public')->put('banners/' . $filename, $res->body());

                    // update event to point to local banner
                    $e->banner = $filename;
                    $e->save();
                }
            } catch (\Exception $ex) {
                // if download fails, leave existing banner or picsum external fallback
                continue;
            }

            // small pause to be polite to the remote service
            usleep(20000);
        }
    }
}
