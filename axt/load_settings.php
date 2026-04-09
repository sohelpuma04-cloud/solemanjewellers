<?php
if (file_exists('settings.json')) {
    echo file_get_contents('settings.json');
} else {
    echo json_encode(['entryPrice' => '', 'stopLoss' => '']);
}
?>