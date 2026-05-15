<?php
require 'vendor/autoload.php';
$app = require_once('bootstrap/app.php');
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = \App\Models\Event::all();
echo "Events and their scanner codes:\n";
foreach ($events as $event) {
    $code = $event->scanner_code ?: 'NO CODE';
    echo "{$event->id}. {$event->title} -> {$code}\n";
}
