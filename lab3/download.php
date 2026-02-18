<?php
// 1. Проверяем, передан ли параметр file
if (!isset($_GET['file']) || $_GET['file'] === '') {
    http_response_code(400);
    exit('Файл не указан');
}

// 2. Защита от path traversal (только имя файла)
$requested = basename($_GET['file']);

// 3. Путь к папке с файлами
$filesDir = __DIR__ . '/files/';
$fullPath = $filesDir . $requested;

// 4. Проверяем, существует ли файл
if (!file_exists($fullPath)) {
    http_response_code(404);
    exit('Файл не найден');
}

// 5. Собираем данные для лога
$date = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Формат: имя_файла;дата/время;ip;
$line = $requested . ';' . $date . ';' . $ip . ";\n";

// 6. Запись в лог-файл
$logFile = __DIR__ . '/log.txt';
file_put_contents($logFile, $line, FILE_APPEND);

// 7. Редирект на скачивание
header('Location: files/' . rawurlencode($requested));
exit;