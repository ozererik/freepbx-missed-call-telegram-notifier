<?php
// Config
$dbhost = 'localhost';
$dbname = 'asteriskcdrdb';
$dbuser = 'freepbxuser'; //dbuser bilgisi girilecek
$dbpass = '********'; //db şifresi girilecek.

// Takip edilecek hedef numaralar. Biz burada santralden temsilcinin özel telefonuna giden çağrıları takip edip Telegram grubuna bildirim atacağız. 10 dakikada 1 CDR tablosunda No Answer arayacağız. BTK kuralları gereği CID'yi santral dışında bir hedefe gönderemiyoruz. Bunun yerine Telegram'dan bildirim atarak temsilciye bilgi veriyoruz.
$targets = array(
    '0549624****' => 'Cihan', //buraya hedef telefon numarasını yazın. Inbound Routes'ta bulunan herhangi bir numara olabilir. Misc Destination olarak verdiğiniz bir numarada olabilir.
    '0549624****' => 'Demet'
);

// Telegram bot bilgileri
$botToken = '7809******:AAGhb42********'; // BotFather ile alınan token buraya girilecek
$chatId = '-48877*****'; // Telegram grubu kurulup grup chat ID buraya girilecek. 

// Veritabanı bağlantısı
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Daha önce bildirim gönderilen çağrıları log dosyasında tut
$logFile = '/var/log/missed_calls_sent.log';
$loggedCalls = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : array();

foreach ($targets as $dstNumber => $label) {
    $stmt = $pdo->prepare("
        SELECT calldate, cnam, dst
        FROM cdr
        WHERE dst = :dst
          AND disposition = 'NO ANSWER'
          AND calldate >= NOW() - INTERVAL 10 MINUTE
        ORDER BY calldate DESC
    ");
    $stmt->execute(array(':dst' => $dstNumber));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $timestamp = $row['calldate'];
        $caller = $row['cnam'];
        $target = $row['dst'];
        $logEntry = $timestamp . ' - ' . $caller . ' > ' . $target;

        if (!in_array($logEntry, $loggedCalls)) {
            // Mesajı hazırla
            $msg = "  Cevapsız Çağrı - " . $label . "\n"
                 . "Tarih: " . $timestamp . "\n"
                 . "Arayan: " . $caller . "\n"
                 . "Hedef: " . $target;

            // Telegram'a gönder
            $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";
            $data = array(
                'chat_id' => $chatId,
                'text' => $msg,
                'parse_mode' => 'HTML'
            );

            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
					'content' => http_build_query($data)
                )
            );
            file_get_contents($url, false, stream_context_create($options));

            // Loga ekle
            file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
        }
    }
}
?>
