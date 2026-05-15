<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Event;

class CinematicImageService
{
    /**
     * Category-based cinematic image search queries
     */
    private $categoryImageKeywords = [
        'concert' => [
            'cinematic concert stage lighting crowd',
            'live music performance stage lights',
            'DJ stage atmosphere spotlights',
            'concert crowd energy festival'
        ],
        'music' => [
            'cinematic live music stage performance',
            'concert festival crowd energy',
            'stage lighting music performance'
        ],
        'nightlife' => [
            'luxury nightclub purple neon lights',
            'upscale nightclub atmosphere crowd',
            'premium club dancing neon',
            'luxury lounge nightlife'
        ],
        'party' => [
            'exclusive party celebration premium',
            'luxury party event atmosphere',
            'celebration crowd energy premium'
        ],
        'conference' => [
            'elegant business conference stage',
            'professional presentation modern auditorium',
            'corporate event luxury hall',
            'business conference elegant stage'
        ],
        'seminar' => [
            'professional seminar auditorium stage',
            'business presentation elegant setup'
        ],
        'workshop' => [
            'modern workshop creative space',
            'professional workshop atmosphere'
        ],
        'wedding' => [
            'elegant wedding ceremony decoration',
            'luxury wedding venue elegant',
            'romantic wedding celebration premium',
            'wedding reception elegant hall'
        ],
        'sports' => [
            'stadium crowd energy competitive',
            'sports arena lighting crowd',
            'championship game atmosphere crowd'
        ],
        'festival' => [
            'outdoor music festival crowd atmosphere',
            'festival stage lighting celebration',
            'festival crowd energy music'
        ],
        'exhibition' => [
            'modern art gallery exhibition elegant',
            'exhibition space professional display',
            'gallery lighting professional'
        ],
        'religious' => [
            'peaceful spiritual gathering elegant',
            'church ceremony elegant atmosphere',
            'spiritual gathering peaceful elegant'
        ],
        'corporate' => [
            'corporate event elegant luxury hall',
            'business event professional celebration',
            'corporate gathering premium'
        ],
        'other' => [
            'premium event elegant atmosphere',
            'luxury celebration crowd energy',
            'cinematic event professional lighting'
        ]
    ];

    /**
     * Generate search query for event
     */
    public function generateSearchQuery(Event $event): string
    {
        $category = 'other';
        
        // Try to detect category from event relationship
        if ($event->categories && $event->categories->count() > 0) {
            $cat = $event->categories->first();
            $category = strtolower($cat->slug ?? $cat->name ?? 'other');
        }

        // Get category keywords or use defaults
        $keywords = $this->getCategoryKeywords($category);
        
        // Select a random keyword variation for variety
        $searchQuery = $keywords[array_rand($keywords)];
        
        // If title has strong indicators, enhance the query
        $titleLower = strtolower($event->title);
        
        // Extract important keywords from title
        $titleKeywords = $this->extractKeywords($titleLower);
        if (!empty($titleKeywords)) {
            $searchQuery = implode(' ', array_slice($titleKeywords, 0, 2)) . ' ' . $keywords[array_rand($keywords)];
        }
        
        return $searchQuery;
    }

    /**
     * Extract important keywords from title
     */
    private function extractKeywords(string $title): array
    {
        $keywords = [];
        $words = preg_split('/[\s\-\'",!?\.]+/', $title);
        
        $stopwords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'at', 'to', 'on', 'by', 'for', 'is', 'be'];
        
        foreach ($words as $word) {
            if (strlen($word) > 3 && !in_array(strtolower($word), $stopwords)) {
                $keywords[] = strtolower($word);
            }
        }
        
        return array_slice($keywords, 0, 3);
    }

    /**
     * Get category keywords
     */
    private function getCategoryKeywords(string $category): array
    {
        return $this->categoryImageKeywords[strtolower($category)] ?? $this->categoryImageKeywords['other'];
    }

    /**
     * Search for cinematic image using Unsplash API
     */
    public function searchUnsplashImage(string $query): ?array
    {
        try {
            $apiKey = config('services.unsplash.access_key');
            
            if (!$apiKey) {
                return null;
            }

            $response = Http::timeout(10)->get('https://api.unsplash.com/search/photos', [
                'query' => $query,
                'per_page' => 5,
                'order_by' => 'relevant',
                'client_id' => $apiKey,
            ]);

            if ($response->successful() && count($response['results']) > 0) {
                // Select best image based on quality metrics
                return $this->selectBestImage($response['results']);
            }
        } catch (\Throwable $e) {
            \Log::warning('Unsplash image search failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Search for cinematic image using Pexels API
     */
    public function searchPexelsImage(string $query): ?array
    {
        try {
            $apiKey = config('services.pexels.api_key');
            
            if (!$apiKey) {
                return null;
            }

            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => $apiKey])
                ->get('https://api.pexels.com/v1/search', [
                    'query' => $query,
                    'per_page' => 5,
                    'orientation' => 'landscape'
                ]);

            if ($response->successful() && count($response['photos']) > 0) {
                $photos = array_map(function($photo) {
                    return [
                        'url' => $photo['src']['large'],
                        'photographer' => $photo['photographer'],
                        'source' => 'pexels',
                        'width' => $photo['width'],
                        'height' => $photo['height']
                    ];
                }, $response['photos']);

                return $this->selectBestImageFromPhotos($photos);
            }
        } catch (\Throwable $e) {
            \Log::warning('Pexels image search failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Select best image from search results
     */
    private function selectBestImage(array $results): ?array
    {
        // Score images based on quality metrics
        $scored = array_map(function($img) {
            $score = 0;
            
            // Prefer higher resolution
            $score += ($img['width'] * $img['height']) / 1000000;
            
            // Prefer images with good aspect ratio (16:9)
            $ratio = $img['width'] / $img['height'];
            if ($ratio >= 1.5 && $ratio <= 1.9) {
                $score += 10;
            }
            
            // Prefer images with higher likes (indicates quality)
            $score += $img['likes'] / 100;
            
            return [
                'score' => $score,
                'data' => [
                    'url' => $img['urls']['regular'],
                    'full_url' => $img['urls']['full'],
                    'photographer' => $img['user']['name'],
                    'photographer_url' => $img['user']['links']['html'],
                    'source' => 'unsplash',
                    'width' => $img['width'],
                    'height' => $img['height']
                ]
            ];
        }, $results);

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        return count($scored) > 0 ? $scored[0]['data'] : null;
    }

    /**
     * Select best image from photos
     */
    private function selectBestImageFromPhotos(array $photos): ?array
    {
        // Return the first one (already good quality from Pexels)
        return count($photos) > 0 ? $photos[0] : null;
    }

    /**
     * Get cinematic image for event (with caching and fallbacks)
     */
    public function getEventImage(Event $event): string
    {
        // Cache key
        $cacheKey = 'event_image_' . $event->id;
        
        // Check cache first
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Generate search query
        $query = $this->generateSearchQuery($event);

        // Try Unsplash first
        $image = $this->searchUnsplashImage($query);

        // Fallback to Pexels
        if (!$image) {
            $image = $this->searchPexelsImage($query);
        }

        // If we found an image, return it and cache for 7 days
        if ($image) {
            $url = $image['url'];
            Cache::put($cacheKey, $url, now()->addDays(7));
            return $url;
        }

        // Ultimate fallback: use Picsum with seed
        $fallbackUrl = $this->generateCinematicFallback($event);
        Cache::put($cacheKey, $fallbackUrl, now()->addDays(7));
        
        return $fallbackUrl;
    }

    /**
     * Generate cinematically-styled fallback image
     */
    private function generateCinematicFallback(Event $event): string
    {
        // Use a higher quality fallback with cinema-appropriate styling
        $seed = 'cinematic-event-' . $event->id;
        
        // Use different seeds based on category for variety
        $category = 'other';
        if ($event->categories && $event->categories->count() > 0) {
            $category = strtolower($event->categories->first()->slug ?? 'other');
        }
        
        // Create deterministic but varied seeds
        $fallbackSeeds = [
            'concert' => 'concert-stage-lights',
            'nightlife' => 'luxury-nightclub',
            'wedding' => 'elegant-wedding',
            'conference' => 'professional-event',
            'sports' => 'stadium-crowd'
        ];

        $baseSeed = $fallbackSeeds[$category] ?? 'premium-event';
        $finalSeed = $baseSeed . '-' . $event->id;

        // Use Picsum for fallback with larger size and deterministic seed
        return "https://picsum.photos/seed/{$finalSeed}/1400/800";
    }

    /**
     * Preload image for faster rendering
     */
    public function saveImageCache(Event $event, string $imageUrl): void
    {
        $cacheKey = 'event_image_' . $event->id;
        Cache::put($cacheKey, $imageUrl, now()->addDays(30));
    }

    /**
     * Clear image cache if needed
     */
    public function clearImageCache(Event $event): void
    {
        $cacheKey = 'event_image_' . $event->id;
        Cache::forget($cacheKey);
    }
}
