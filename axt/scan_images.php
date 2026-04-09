<?php
header('Content-Type: application/json');

// ছবির ফোল্ডারের নাম
$dir = "images/";
$images = [];

if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            // শুধুমাত্র ছবি ফাইলগুলো চেক করুন
            if (preg_match("/\.(jpg|jpeg|png|gif|webp)$/i", $file)) {
                $images[] = $file;
            }
        }
    }
}
// ছবির লিস্ট রিটার্ন করুন
echo json_encode(array_values($images));
?>