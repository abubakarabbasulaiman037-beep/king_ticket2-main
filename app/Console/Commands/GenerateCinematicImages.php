<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\CinematicImageService;
use Illuminate\Console\Command;

class GenerateCinematicImages extends Command
{
    protected $signature = 'cinematic:generate {--event-id= : Generate image for specific event}';

    protected $description = 'Pre-generate cinematic images for events';

    public function handle()
    {
        $service = new CinematicImageService();

        if ($this->option('event-id')) {
            // Generate for specific event
            $event = Event::findOrFail($this->option('event-id'));
            $this->generateForEvent($event, $service);
        } else {
            // Generate for all events
            $events = Event::where('date', '>', now())->get();
            $this->info("Generating cinematic images for {$events->count()} upcoming events...");

            foreach ($events as $event) {
                $this->generateForEvent($event, $service);
            }

            $this->info('✓ All cinematic images generated successfully!');
        }
    }

    private function generateForEvent(Event $event, CinematicImageService $service)
    {
        $this->info("Generating image for: {$event->title}");
        
        $query = $service->generateSearchQuery($event);
        $this->line("  Search query: {$query}");

        // Try Unsplash
        $image = $service->searchUnsplashImage($query);

        // Fallback to Pexels
        if (!$image) {
            $image = $service->searchPexelsImage($query);
        }

        if ($image) {
            $service->saveImageCache($event, $image['url']);
            $source = $image['source'] ?? 'unknown';
            $photographer = $image['photographer'] ?? 'Unknown';
            $this->line("  ✓ Found from {$source} by {$photographer}");
        } else {
            $fallback = $service->generateCinematicFallback($event);
            $service->saveImageCache($event, $fallback);
            $this->line("  ✓ Using fallback (Picsum Photos)");
        }
    }
}
