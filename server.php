<?php
header('Content-Type: application/json');

// ডাটাবেস সংযোগ (MySQL/MariaDB ব্যবহার করতে চাইলে)
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $dbname = "soleman_jewellers";
// 
// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Simple file-based storage (ডেমোর জন্য)
$dataFile = 'data.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // ডাটা লোড করা
    if (file_exists($dataFile)) {
        $data = file_get_contents($dataFile);
        echo $data;
    } else {
        echo json_encode(['bills' => '[]', 'loans' => '[]', 'settings' => '{}']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ডাটা সেভ করা
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data) {
        file_put_contents($dataFile, json_encode($data));
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
    }
}
?>