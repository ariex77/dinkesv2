<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing update API endpoint...\n\n";
    
    $url = 'https://presensi.adamadifa.site/api/update/check';
    $params = ['current_version' => '3.0.3'];
    
    echo "URL: $url\n";
    echo "Params: " . json_encode($params) . "\n\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(30)->get($url, $params);
    
    echo "Status Code: " . $response->status() . "\n";
    echo "Response Body:\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n\n";
    
    if ($response->successful()) {
        echo "✓ Request successful!\n";
    } else {
        echo "✗ Request failed with status: " . $response->status() . "\n";
    }
    
} catch (\Illuminate\Http\Client\ConnectionException $e) {
    echo "✗ Connection Error: " . $e->getMessage() . "\n";
    echo "\nPossible causes:\n";
    echo "- Server is down or unreachable\n";
    echo "- SSL certificate issue\n";
    echo "- Network/firewall blocking the request\n";
    echo "- DNS resolution problem\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Type: " . get_class($e) . "\n";
}
