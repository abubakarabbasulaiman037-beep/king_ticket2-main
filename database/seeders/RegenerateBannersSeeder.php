<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RegenerateBannersSeeder extends Seeder
{
    public function run(): void
    {
        $bannersPath = storage_path('app/public/banners');
        if (!File::exists($bannersPath)) {
            File::makeDirectory($bannersPath, 0755, true);
        }

        $events = Event::all();
        foreach ($events as $e) {
            $title = $e->title ?: 'Event';
            $kind = stripos($title, 'cinema') !== false || stripos($title, 'movie') !== false ? 'Cinema' : 'Event';

            $width = 1200; $height = 600;
            $img = imagecreatetruecolor($width, $height);

            // Pick background color based on kind and id
            $seed = crc32($title . $e->id);
            srand($seed);
            $r = 80 + rand(0, 120);
            $g = 80 + rand(0, 120);
            $b = 80 + rand(0, 120);
            $bg = imagecolorallocate($img, $r, $g, $b);
            imagefilledrectangle($img, 0, 0, $width, $height, $bg);

            // overlay darker strip at bottom
            $strip = imagecolorallocate($img, max(0,$r-40), max(0,$g-40), max(0,$b-40));
            imagefilledrectangle($img, 0, $height-140, $width, $height, $strip);

            // Text colors
            $white = imagecolorallocate($img, 255, 255, 255);
            $yellow = imagecolorallocate($img, 255, 215, 0);

            // Title using imagestring (large)
            $titleText = $title;
            $maxCharsPerLine = 40;
            $lines = [];
            while (strlen($titleText) > $maxCharsPerLine) {
                $pos = strrpos(substr($titleText, 0, $maxCharsPerLine), ' ');
                if ($pos === false) $pos = $maxCharsPerLine;
                $lines[] = trim(substr($titleText, 0, $pos));
                $titleText = trim(substr($titleText, $pos));
            }
            if (strlen($titleText)) $lines[] = $titleText;

            // Write lines starting near bottom
            $lineCount = count($lines);
            $font = 5; // built-in font size
            $lineHeight = imagefontheight($font) + 6;
            $startY = $height - 120 + 12;
            foreach ($lines as $i => $line) {
                $y = $startY + ($i * $lineHeight);
                $textWidth = imagefontwidth($font) * strlen($line);
                $x = ($width - $textWidth) / 2;
                imagestring($img, $font, (int)$x, (int)$y, $line, $white);
            }

            // Small meta text
            $meta = strtoupper($kind) . ' • ' . date('M d, Y', strtotime($e->date));
            imagestring($img, 3, 18, $height - 30, $meta, $yellow);

            $filename = 'banner_' . $e->id . '_' . time() . '.jpg';
            $dest = $bannersPath . DIRECTORY_SEPARATOR . $filename;

            imagejpeg($img, $dest, 85);
            imagedestroy($img);

            // update event banner field
            $e->banner = $filename;
            $e->save();

            // small pause
            usleep(15000);
        }
    }
}
