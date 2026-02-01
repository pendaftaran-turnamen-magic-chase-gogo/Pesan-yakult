<?php
// proxy.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

// KONFIGURASI BOT TELEGRAM
$TG_BOT_TOKEN = "7635408983:AAHrM9l9mXMYMrX6K6IP_my1tR-gHCmADBM"; 
$TG_CHAT_ID   = "-1002406787864";

if (isset($_POST['action']) && $_POST['action'] === 'send_telegram') {
    $message = $_POST['caption'] ?? 'Pesanan Baru';
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;
    
    // Tombol Buka Maps
    $reply_markup = null;
    if ($lat && $lng) {
        $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}";
        $reply_markup = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '📍 Buka Lokasi Customer', 'url' => $mapsUrl]
                ]
            ]
        ]);
    }

    // Jika ada Foto (Bukti Transfer)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $url = "https://api.telegram.org/bot$TG_BOT_TOKEN/sendPhoto";
        $cFile = new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type'], $_FILES['photo']['name']);
        $data = [
            'chat_id' => $TG_CHAT_ID, 
            'photo' => $cFile, 
            'caption' => $message, 
            'parse_mode' => 'HTML'
        ];
    } 
    // Jika Pesan Teks Saja (Order Cash/Notifikasi)
    else {
        $url = "https://api.telegram.org/bot$TG_BOT_TOKEN/sendMessage";
        $data = [
            'chat_id' => $TG_CHAT_ID, 
            'text' => $message, 
            'parse_mode' => 'HTML'
        ];
    }

    if ($reply_markup) {
        $data['reply_markup'] = $reply_markup;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    
    echo $result;
    exit;
}
?>
