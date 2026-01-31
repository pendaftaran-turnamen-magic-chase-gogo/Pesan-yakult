<?php
// proxy.php
// Header dinamis tergantung tipe request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");
    exit;
}

// KONFIGURASI TELEGRAM
$TG_BOT_TOKEN = "7635408983:AAHrM9l9mXMYMrX6K6IP_my1tR-gHCmADBM"; 
$TG_CHAT_ID   = "-1002406787864";

// --- LOGIKA 1: REQUEST JSON (UNTUK QRIS) ---
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if ($input && isset($input['qris_statis'])) {
    header('Content-Type: application/json');
    $apiUrl = "https://qrisku.my.id/api";
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    if(curl_errno($ch)) echo json_encode(['status' => 'error', 'message' => curl_error($ch)]);
    else echo $response;
    curl_close($ch);
    exit;
}

// --- LOGIKA 2: REQUEST FORM-DATA (UNTUK TELEGRAM) ---
if (isset($_POST['action']) && $_POST['action'] === 'send_telegram') {
    header('Content-Type: application/json');
    
    $message = $_POST['caption'] ?? 'Pesanan Baru';
    
    // Cek apakah ada file gambar (Bukti Transfer)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $url = "https://api.telegram.org/bot$TG_BOT_TOKEN/sendPhoto";
        $cFile = new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type'], $_FILES['photo']['name']);
        $data = [
            'chat_id' => $TG_CHAT_ID,
            'photo'   => $cFile,
            'caption' => $message,
            'parse_mode' => 'HTML'
        ];
    } else {
        // Jika tidak ada gambar (misal Cash), kirim pesan saja
        $url = "https://api.telegram.org/bot$TG_BOT_TOKEN/sendMessage";
        $data = [
            'chat_id' => $TG_CHAT_ID,
            'text'    => $message,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => false
        ];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    echo $result;
    exit;
}
?>
