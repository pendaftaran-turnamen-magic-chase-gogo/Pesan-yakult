<?php
// proxy.php
header('Content-Type: application/json');

// Menerima data JSON dari index.php
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

// Validasi input
if (!isset($input['amount']) || !isset($input['qris_statis'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

$apiUrl = "https://qrisku.my.id/api";

$data = [
    "amount" => $input['amount'],
    "qris_statis" => $input['qris_statis']
];

// Inisialisasi cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// Eksekusi
$response = curl_exec($ch);

// Cek Error
if(curl_errno($ch)){
    echo json_encode(['status' => 'error', 'message' => 'Curl error: ' . curl_error($ch)]);
} else {
    // Kembalikan response asli dari API Qrisku ke Javascript kita
    echo $response;
}

curl_close($ch);
?>
