<?php
# Author  : Nitish Kumar Diwakar
# Email   : nitishkumardiwakar@gmail.com
# Github  : https://github.com/NitishDiwakar
# Project : LAN File Sharing
# Licence : MIT

$uploadDir = __DIR__ . "/uploads/";

if (!isset($_GET['file'])) {
    exit;
}

$file = basename($_GET['file']);
$filePath = $uploadDir . $file;

if (!file_exists($filePath)) {
    exit;
}

$size = filesize($filePath);
$mime = mime_content_type($filePath);

header("Content-Type: $mime");
header("Accept-Ranges: bytes");

if (isset($_SERVER['HTTP_RANGE'])) {

    list($unit, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);

    list($start, $end) = explode('-', $range);

    $start = intval($start);
    $end = $end ? intval($end) : $size - 1;

    $length = $end - $start + 1;

    header("HTTP/1.1 206 Partial Content");
    header("Content-Length: $length");
    header("Content-Range: bytes $start-$end/$size");

    $fp = fopen($filePath, 'rb');
    fseek($fp, $start);

    while (!feof($fp) && ($pos = ftell($fp)) <= $end) {
        echo fread($fp, 8192);
        flush();
    }

    fclose($fp);
    exit;
}

header("Content-Length: $size");
readfile($filePath);
exit;
