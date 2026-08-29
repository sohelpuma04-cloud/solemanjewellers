<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

function readPriceFile() {
    global $dataDir;
    $path = $dataDir . '/prices.json';
    if (file_exists($path)) {
        $content = file_get_contents($path);
        return json_decode($content, true) ?: [];
    }
    return [];
}

function writePriceFile($data) {
    global $dataDir;
    $path = $dataDir . '/prices.json';
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$current = readPriceFile();

// নতুন মান মার্জ করুন
foreach ($input as $key => $value) {
    $current[$key] = $value;
}

writePriceFile($current);

// একই সাথে price.json ফাইলও আপডেট করুন (HTML যে ফাইল থেকে পড়ে)
$priceJsonPath = __DIR__ . '/price.json';
file_put_contents($priceJsonPath, json_encode($current, JSON_PRETTY_PRINT));

echo json_encode(['status' => 'success', 'data' => $current]);
?>
