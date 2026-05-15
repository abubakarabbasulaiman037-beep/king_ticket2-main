<?php

namespace App\Services;

class AiAssistantService
{
    // Local heuristic assistant for event creation suggestions
    public function suggestPrice(array $eventData)
    {
        // Simple heuristic: base on capacity and popularity fields
        $base = $eventData['base_price'] ?? 1000; // assume NGN
        $capacity = $eventData['capacity'] ?? 100;
        $popularity = $eventData['popularity'] ?? 1; // 1..5

        $price = $base * (1 + ($popularity - 1) * 0.15) * (1 + max(0, (100 - $capacity) / 200));
        return round($price, 2);
    }

    public function suggestTime(array $eventData)
    {
        // Prefer evenings for music/entertainment, mornings for business/education
        $category = strtolower($eventData['category'] ?? 'music');
        if (in_array($category, ['music','entertainment','sport'])) {
            return '19:00';
        }
        if (in_array($category, ['business','tech','education'])) {
            return '10:00';
        }
        return '18:00';
    }

    public function generateDescription(array $eventData)
    {
        $title = $eventData['title'] ?? 'Event';
        $what = $eventData['what'] ?? 'an exciting experience';
        $when = $eventData['date'] ?? 'soon';
        $place = $eventData['location'] ?? 'your city';

        return "$title is $what happening on $when at $place. Join us for an unforgettable experience!";
    }
}
