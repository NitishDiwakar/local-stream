<?php
# Author  : Nitish Kumar Diwakar
# Email   : nitishkumardiwakar@gmail.com
# Github  : https://github.com/NitishDiwakar
# Project : LAN File Sharing
# Licence : MIT

$uploadDir = __DIR__ . "/uploads/";

if (!isset($_GET['file'])) {
    exit("No file specified.");
}

$file = basename($_GET['file']);
$filePath = $uploadDir . $file;

if (!file_exists($filePath)) {
    exit("File not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Play Video</title>
<style>
body {
    margin: 0;
    background: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
video {
    width: 100%;
    height: 100%;
}
</style>
</head>
<body>

<video controls autoplay>
    <source src="stream.php?file=<?php echo rawurlencode($file); ?>" type="video/mp4">
</video>

</body>
</html>
