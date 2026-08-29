<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// ডেটা সংরক্ষণের জন্য ফোল্ডার
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

function readDataFile($filename) {
    global $dataDir;
    $path = $dataDir . '/' . $filename;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        return json_decode($content, true) ?: [];
    }
    return [];
}

function writeDataFile($filename, $data) {
    global $dataDir;
    $path = $dataDir . '/' . $filename;
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // সব ডেটা একসাথে ফেরত দিন
    $response = [
        'bills'    => json_encode(readDataFile('bills.json')),
        'loans'    => json_encode(readDataFile('loans.json')),
        'settings' => json_encode(readDataFile('settings.json')),
        'prices'   => json_encode(readDataFile('prices.json'))
    ];
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    // প্রতিটি কী আলাদা ফাইলে সংরক্ষণ করুন
    if (isset($input['bills'])) {
        writeDataFile('bills.json', json_decode($input['bills'], true) ?: []);
    }
    if (isset($input['loans'])) {
        writeDataFile('loans.json', json_decode($input['loans'], true) ?: []);
    }
    if (isset($input['settings'])) {
        writeDataFile('settings.json', json_decode($input['settings'], true) ?: []);
    }
    if (isset($input['prices'])) {
        writeDataFile('prices.json', json_decode($input['prices'], true) ?: []);
    }

    echo json_encode(['status' => 'success']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method Not Allowed']);
?>
