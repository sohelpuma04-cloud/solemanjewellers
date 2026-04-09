<?php
header('Content-Type: application/json');

$target_dir = "images/";
// ফোল্ডার না থাকলে তৈরি করে নেবে
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_FILES['image'])) {
    // ফাইলের নাম ইউনিক করার জন্য টাইমস্ট্যাম্প যোগ করা হলো
    $filename = time() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    
    // ফাইল মুভ করা হচ্ছে
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo json_encode(["status" => "success", "url" => $target_file]);
    } else {
        echo json_encode(["status" => "error", "message" => "Sorry, upload failed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file received."]);
}
?>