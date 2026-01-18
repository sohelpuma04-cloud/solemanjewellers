<?php
// save_price.php
header('Content-Type: application/json');

// ইনপুট ডাটা নেওয়া
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['goldPrice'])) {
    // ডাটা price.json ফাইলে সেভ করা
    $jsonData = json_encode(['goldPrice' => $data['goldPrice']], JSON_PRETTY_PRINT);
    
    if(file_put_contents('price.json', $jsonData)) {
        echo json_encode(['status' => 'success', 'message' => 'Price saved']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>