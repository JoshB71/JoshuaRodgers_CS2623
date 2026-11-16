<?php
// SSP08 - Exercise 16-1
// File Input and Output
// Compatible with PHP 5.5 and PHP 8

$dataDir = __DIR__ . '/data/';
$logFile = $dataDir . 'log.txt';

$message = '';
$fileContent = '';
$files = array();
$fileSize = 0;

// Create the data folder if it does not exist
if (!is_dir($dataDir)) {
    if (!@mkdir($dataDir, 0755, true)) {
        $message = 'Could not create the data folder.';
    }
}

// Download the log file
if (isset($_GET['download']) && $_GET['download'] === '1') {

    if (is_file($logFile) && is_readable($logFile)) {

        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Disposition: attachment; filename="log.txt"');

        readfile($logFile);
        exit;

    } else {
        $message = 'There is no log file to download.';
    }
}

// Process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action'])
        ? $_POST['action']
        : '';

    // Save a new entry
    if ($action === 'save') {

        $entry = trim((string)filter_input(
            INPUT_POST,
            'entry',
            FILTER_UNSAFE_RAW
        ));

        if ($entry === '') {

            $message = 'Entry cannot be empty.';

        } elseif (strlen($entry) > 200) {

            $message = 'Entry must be 200 characters or less.';

        } elseif (!is_dir($dataDir)) {

            $message = 'The data folder is unavailable.';

        } else {

            // Use Oklahoma/Central time
            $tz = new DateTimeZone('America/Chicago');
            $now = new DateTime('now', $tz);

            // Keep each entry on one line
            $entry = str_replace(
                array("\r", "\n"),
                ' ',
                $entry
            );

            $line = $now->format('Y-m-d H:i:s')
                  . ' - '
                  . $entry
                  . PHP_EOL;

            $saved = @file_put_contents(
                $logFile,
                $line,
                FILE_APPEND | LOCK_EX
            );

            if ($saved === false) {

                $message = 'Unable to save the entry. Check folder permissions.';

            } else {

                // Keep the file permissions restricted
                @chmod($logFile, 0644);

                $message = 'Entry saved successfully!';
            }
        }
    }

    // Clear the log
    if ($action === 'clear') {

        if (!is_dir($dataDir)) {

            $message = 'The data folder is unavailable.';

        } elseif (@file_put_contents($logFile, '', LOCK_EX) === false) {

            $message = 'Unable to clear the log.';

        } else {

            @chmod($logFile, 0644);

            $message = 'Log cleared successfully!';
        }
    }
}

// Read existing log contents
if (file_exists($logFile)) {

    $content = @file_get_contents($logFile);

    if ($content !== false) {
        $fileContent = $content;
    }

    clearstatcache(true, $logFile);

    $fileSize = (int)filesize($logFile);
}

// List the files in the data directory
if (is_dir($dataDir)) {

    $allFiles = scandir($dataDir);

    if ($allFiles !== false) {

        $files = array_filter(
            $allFiles,
            function ($filename) use ($dataDir) {
                return is_file($dataDir . $filename);
            }
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Exercise 16-1 - File I/O</title>
</head>

<body>

<h1>File Log Demo</h1>

<?php if ($message !== ''): ?>

    <p>
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>

<?php endif; ?>

<h2>Add a Log Entry</h2>

<form method="post">

    <p>
        <label for="entry">New Log Entry:</label>

        <input type="text"
               id="entry"
               name="entry"
               maxlength="200"
               required>
    </p>

    <button type="submit" name="action" value="save">
        Save Entry
    </button>

</form>

<h2>Log Contents</h2>

<?php if ($fileContent !== ''): ?>

    <pre><?= htmlspecialchars($fileContent, ENT_QUOTES, 'UTF-8') ?></pre>

<?php else: ?>

    <p>No entries yet.</p>

<?php endif; ?>

<h2>File Information</h2>

<p>
    File Size:
    <?= number_format($fileSize) ?> bytes
</p>

<h2>Log Actions</h2>

<form method="post">

    <button type="submit" name="action" value="clear">
        Clear Log
    </button>

</form>

<p>
    <a href="?download=1">Download Log</a>
</p>

<h2>Files in Data Directory</h2>

<ul>

<?php foreach ($files as $filename): ?>

    <li>
        <?= htmlspecialchars(
            basename($filename),
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </li>

<?php endforeach; ?>

</ul>

</body>
</html>
