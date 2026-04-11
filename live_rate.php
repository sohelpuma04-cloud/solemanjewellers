<?php
// live_rate.php নামে সেভ করুন

// USD এর বদলে INR ব্যবহার করা হয়েছে
const RATES_API_URL = 'https://data-asg.goldprice.org/dbXRates/INR';

const OZ_TO_GRAM = 31.1035;
const GRAM_TO_KG = 1000;
const TOLA_TO_GRAM = 11.66;

const PURITY_24K = 1.00; 
const PURITY_22K = 0.9167; 
const PURITY_21K = 0.8750; 
const PURITY_18K = 0.7500; 

$ch = curl_init(RATES_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'cURL error: ' . curl_error($ch)]);
    exit();
}
curl_close($ch); 

$apiResponse = json_decode($response, true);
$currAndRateCollection = [];

if(isset($apiResponse['items'])) {
    foreach ($apiResponse['items'] as $item) {
        $currency = $item['curr'];
        $goldPriceOZ = $item['xauPrice']; 
        $silverPriceOZ = $item['xagPrice']; 

        $goldPriceG = $goldPriceOZ / OZ_TO_GRAM; 
        $goldPriceKG = $goldPriceG * GRAM_TO_KG; 
        $goldPriceTola = $goldPriceG * TOLA_TO_GRAM; 

        $silverPriceG = $silverPriceOZ / OZ_TO_GRAM;

        $currAndRateCollection[$currency]['gold_rates'] = [
            'Price_OZ' => round($goldPriceOZ, 2),
            'Price_G' => round($goldPriceG, 2)
        ];
    }
}
echo json_encode($currAndRateCollection, JSON_PRETTY_PRINT);
?>
