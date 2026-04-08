<?php
# Author  : Nitish Kumar Diwakar
# Twitter : https://x.com/NitishDiwakar
# Github  : https://github.com/NitishDiwakar
# Project : LAN File Sharing
# Licence : MIT

$uploadDir = __DIR__ . "/uploads/";
$allowedExtensions = ['mp4', 'mov', 'MOV', 'zip', '7z', 'jpg', 'jpeg', 'png', 'pdf', 'mp3', 'rar', 'txt'];
// 
/* DELETE SINGLE FILE */
if (isset($_POST['delete_file'])) {
    $fileToDelete = basename($_POST['delete_file']);
    $filePath = $uploadDir . $fileToDelete;

    if (file_exists($filePath) && is_file($filePath)) {
        unlink($filePath);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* DELETE ALL FILES */
if (isset($_POST['delete_all'])) {
    foreach (glob($uploadDir . '*') as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
// 

/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedExtensions)) {
        http_response_code(400);
        echo "File type not allowed.";
        exit;
    }

    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        touch($targetPath); // sets current time
        echo "Upload successful.";
    } else {
        http_response_code(500);
        echo "Upload failed.";
    }
    exit;
}*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    $files = $_FILES['file'];

    for ($i = 0; $i < count($files['name']); $i++) {

        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

        $fileName = basename($files['name'][$i]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) continue;

        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
            touch($targetPath);
        }
    }

    echo "Upload complete";
    exit;
}

// $files = array_diff(scandir($uploadDir, SCANDIR_SORT_DESCENDING), array('.', '..'));

// $files = array_diff(scandir($uploadDir), array('.', '..'));
// Don't show files start with dot
$files = array_filter(scandir($uploadDir), function($file) use ($uploadDir) {
    return $file !== '.' 
        && $file !== '..'
        && $file[0] !== '.'   // exclude hidden files like .gitignore
        && is_file($uploadDir . $file);
});

/*usort($files, function($a, $b) use ($uploadDir) {
    return filemtime($uploadDir . $b) - filemtime($uploadDir . $a);
});*/


$fileTimes = [];

foreach ($files as $f) {
    $fileTimes[$f] = filemtime($uploadDir . $f);
}

usort($files, function($a, $b) use ($fileTimes) {
    return $fileTimes[$b] <=> $fileTimes[$a];
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Local File Upload</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h1>File Upload</h1>

    <div class="upload-box" id="dropArea">
        <input type="file" id="fileInput" multiple>
        <p id="dropText">Drag & Drop files here<br>or click to select</p>

        <p id="selectedFileName" class="selected-file"></p>

        <button id="uploadBtn" type="button">Upload</button>

        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>

        <p id="status"></p>
    </div>

    <h2>Uploaded Files</h2>

    <form method="POST" onsubmit="return confirm('Delete ALL files?');">
        <button type="submit" name="delete_all" class="clear-btn">
            🧹 Clear All
        </button>
    </form>

    <div class="file-list">
    <?php foreach ($files as $file): 
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    ?>
        <div class="file-item">

            <div class="file-left">

                <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                    <img loading="lazy" src="uploads/<?php echo rawurlencode($file); ?>" class="thumb">

                <?php elseif ($ext === 'mp4'): ?>
                    <!-- <video class="thumb" muted preload="metadata">
                        <source src="uploads/<?php // echo rawurlencode($file); ?>" type="video/mp4">
                    </video> -->
                    <!-- Above was making page load slow -->
                    <div class="file-icon">🎬</div>

                <?php else: ?>
                    <div class="file-icon">📦</div>
                <?php endif; ?>
                <?php 
                $size = filesize($uploadDir . $file);
                $sizeMB = round($size / (1024 * 1024), 2);
                ?>
                <span class="file-name">
                    <?php echo htmlspecialchars($file); ?>
                    (<?php echo $sizeMB; ?> MB)
                </span>

            </div>

            <div class="file-actions">

                <?php // if ($ext === 'mp4' || $ext === 'mov'): ?>
                <?php  if (in_array($ext, ['mp4','mov','3gp','webm','MOV'])): ?>
                    <a href="play.php?file=<?php echo rawurlencode($file); ?>" target="_blank">
                        <button type="button" class="play-btn">▶</button>
                    </a>
                <?php endif; ?>

                <a href="uploads/<?php echo rawurlencode($file); ?>" download>
                    <button type="button" class="download-btn">Download</button>
                </a>

                <form method="POST" onsubmit="return confirm('Delete this file?');">
                    <input type="hidden" name="delete_file" value="<?php echo htmlspecialchars($file); ?>">
                    <button type="submit" class="delete-btn" title="Delete">
                        🗑
                    </button>
                </form>

            </div>

        </div>
    <?php endforeach; ?>
    </div>
</div>

<script>
const allowedExtensions = <?php echo json_encode($allowedExtensions); ?>;
</script>
<script src="script.js"></script>

</body>
</html>
