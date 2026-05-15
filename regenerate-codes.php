<?php
require 'vendor/autoload.php';
$app = require_once('bootstrap/app.php');
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = \App\Models\Event::all();
$count = 0;
foreach ($events as $event) {
    if (!$event->scanner_code) {
        $event->generateScannerCode();
        $count++;
        echo "✓ Event {$event->id} ({$event->title}): {$event->scanner_code}\n";
    }
}
echo "\n✓ Generated scanner codes for {$count} event(s)\n";
