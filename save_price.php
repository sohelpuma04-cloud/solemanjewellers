<?php
header('Content-Type: application/json');

// ১. ফ্রন্টএন্ড থেকে পাঠানো ডাটা (JSON) রিসিভ করা
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true);

if ($inputData) {
    $jsonFile = 'price.json';
    $currentData = [];

    // ২. যদি আগে থেকে price.json ফাইল থাকে, তবে তার ডাটা পড়ে নেওয়া
    if (file_exists($jsonFile)) {
        $fileContent = file_get_contents($jsonFile);
        $currentData = json_decode($fileContent, true);
        if (!is_array($currentData)) {
            $currentData = [];
        }
    }

    // ৩. নতুন আসা দামগুলো পুরানো ডাটার সাথে আপডেট করা
    if (isset($inputData['goldPrice'])) {
        $currentData['goldPrice'] = $inputData['goldPrice'];
    }
    if (isset($inputData['oldGoldPriceGeneral'])) {
        $currentData['oldGoldPriceGeneral'] = $inputData['oldGoldPriceGeneral'];
    }
    if (isset($inputData['oldGoldPriceNakmachi'])) {
        $currentData['oldGoldPriceNakmachi'] = $inputData['oldGoldPriceNakmachi'];
    }
    if (isset($inputData['newSilverPrice'])) {
        $currentData['newSilverPrice'] = $inputData['newSilverPrice'];
    }
    if (isset($inputData['oldSilverPrice'])) {
        $currentData['oldSilverPrice'] = $inputData['oldSilverPrice'];
    }

    // ৪. আপডেট করা ডাটা আবার price.json ফাইলে সেভ করা (JSON_PRETTY_PRINT দিয়ে সুন্দরভাবে সাজিয়ে)
    if (file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT))) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'দামের তালিকা সফলভাবে সেভ হয়েছে!', 
            'data' => $currentData
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'ফাইলে ডাটা সেভ করতে সমস্যা হয়েছে।'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'কোনো সঠিক ডাটা পাওয়া যায়নি।'
    ]);
}
?>
