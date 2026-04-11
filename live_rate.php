<?php

/*#############################
 * Developer: Mohammad Sharaf Ali
 * Designation: Web Developer
 * Version: 2.0
 * 
 * Changes from Version 1.0:
 * - Updated to use a new API endpoint that returns JSON data instead of plain text
 * - Replaced file_get_contents with cURL for improved error handling and performance
 * - Added calculations for gold and silver prices in grams (G), kilograms (KG), and tolas, in addition to ounces (OZ)
 * - Added calculations for gold prices at 24K, 22K, 21K, and 18K purity levels
 * - Included a list of supported currencies for better reference
 * - Formatted final JSON output to 2 decimal places
 * - Grouped data by currency for easier parsing
###############################*/

/**
 * API URL to fetch gold and silver prices
 * 
 * By default, this URL fetches prices in USD
 * Add more currencies by appending them as comma-separated values, e.g., 'PKR,INR,...'
 */
const RATES_API_URL = 'https://data-asg.goldprice.org/dbXRates/USD';

/**
 * List of supported currencies:
 * USD, AED, AFN, ALL, AMD, ANG, AOA, ARS, AUD, AWG, AZN, BAM, BBD, BDT, BGN, 
 * BHD, BIF, BMD, BND, BOB, BRL, BSD, BTN, BWP, BYN, BZD, CAD, CDF, CHF, CLP, 
 * CNY, COP, CRC, CUC, CUP, CVE, CZK, DJF, DKK, DOP, DZD, EGP, ERN, ETB, EUR, 
 * FJD, FKP, GBP, GEL, GGP, GHS, GIP, GMD, GNF, GTQ, GYD, HKD, HNL, HRK, HTG, 
 * HUF, IDR, ILS, IMP, INR, IQD, IRR, ISK, JEP, JMD, JOD, JPY, KES, KGS, KHR, 
 * KMF, KPW, KRW, KWD, KYD, KZT, LAK, LBP, LKR, LRD, LSL, LYD, MAD, MDL, MGA, 
 * MKD, MMK, MNT, MOP, MRU, MUR, MVR, MWK, MXN, MYR, MZN, NAD, NGN, NIO, NOK, 
 * NPR, NZD, OMR, PAB, PEN, PGK, PHP, PKR, PLN, PYG, QAR, RON, RSD, RUB, RWF, 
 * SAR, SBD, SCR, SDG, SEK, SGD, SHP, SLL, SOS, SRD, STD, SVC, SYP, SZL, THB, 
 * TJS, TMT, TND, TOP, TRY, TTD, TWD, TZS, UAH, UGX, USD, UYU, UZS, VEF, VND, 
 * VUV, WST, XAF, XAG, XAU, XCD, XDR, XOF, XPD, XPF, XPT, YER, ZAR, ZMW
 */

/**
 * Conversion constants
 * 
 * - OZ_TO_GRAM: Conversion factor from ounces to grams
 * - GRAM_TO_KG: Conversion factor from grams to kilograms
 * - TOLA_TO_GRAM: Conversion factor from tolas to grams
 */
const OZ_TO_GRAM = 31.1035;
const GRAM_TO_KG = 1000;
const TOLA_TO_GRAM = 11.66;

// Gold purity factors for different carats
const PURITY_24K = 1.00; // 100% pure gold
const PURITY_22K = 0.9167; // 91.67% pure gold
const PURITY_21K = 0.8750; // 87.5% pure gold
const PURITY_18K = 0.7500; // 75% pure gold

// Initialize a cURL session to fetch data from the API
$ch = curl_init(RATES_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the response as a string
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // disable SSL verification (useful for local testing)
$response = curl_exec($ch);

// Handle any cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit();
}

curl_close($ch); // close the cURL session

// Decode the JSON response into a PHP array
$apiResponse = json_decode($response, true);

// Initialize an array to store calculated rates for gold and silver
$currAndRateCollection = [];

/**
 * Calculate gold and silver prices in OZ, grams, kilograms, tolas, and various carats
 */
foreach ($apiResponse['items'] as $item) {
    $currency = $item['curr'];
    $goldPriceOZ = $item['xauPrice']; // gold price per ounce
    $silverPriceOZ = $item['xagPrice']; // silver price per ounce

    // Convert prices to grams, kilograms, and tolas
    $goldPriceG = $goldPriceOZ / OZ_TO_GRAM; // gold price per gram
    $goldPriceKG = $goldPriceG * GRAM_TO_KG; // gold price per kilogram
    $goldPriceTola = $goldPriceG * TOLA_TO_GRAM; // gold price per tola

    // Calculate gold prices for different carats
    $goldPrice24K = $goldPriceOZ * PURITY_24K;
    $goldPrice22K = $goldPriceOZ * PURITY_22K;
    $goldPrice21K = $goldPriceOZ * PURITY_21K;
    $goldPrice18K = $goldPriceOZ * PURITY_18K;

    $silverPriceG = $silverPriceOZ / OZ_TO_GRAM;
    $silverPriceKG = $silverPriceG * GRAM_TO_KG;
    $silverPriceTola = $silverPriceG * TOLA_TO_GRAM;

    // Store gold prices grouped by currency
    $currAndRateCollection[$currency]['gold_rates'] = [
        'Price_OZ' => round($goldPriceOZ, 2),
        'Price_G' => round($goldPriceG, 2),
        'Price_KG' => round($goldPriceKG, 2),
        'Price_Tola' => round($goldPriceTola, 2),
        'Price_24K' => round($goldPrice24K, 2),
        'Price_22K' => round($goldPrice22K, 2),
        'Price_21K' => round($goldPrice21K, 2),
        'Price_18K' => round($goldPrice18K, 2)
    ];

    // Store silver prices grouped by currency
    $currAndRateCollection[$currency]['silver_rates'] = [
        'Price_OZ' => round($silverPriceOZ, 2),
        'Price_G' => round($silverPriceG, 2),
        'Price_KG' => round($silverPriceKG, 2),
        'Price_Tola' => round($silverPriceTola, 2)
    ];
}

// Output the final JSON
echo json_encode($currAndRateCollection, JSON_PRETTY_PRINT);
