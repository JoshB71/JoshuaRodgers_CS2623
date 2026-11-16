<?php
// SSP09 - Exercise 17-1
// Strings, Numbers, and Dates
// PHP 8 with compatibility support for Apollo PHP 5.5

// Compatibility functions for older PHP versions
if (!function_exists('str_contains')) {
    function str_contains($text, $search) {
        return $search === '' || strpos($text, $search) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with($text, $search) {
        return substr($text, 0, strlen($search)) === $search;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($text, $search) {
        return $search === '' ||
            substr($text, -strlen($search)) === $search;
    }
}

// Secure random-number compatibility for PHP 5.5
if (!function_exists('random_int')) {
    function random_int($min, $max) {
        $range = $max - $min + 1;
        $limit = 4294967296 - (4294967296 % $range);

        do {
            $strong = false;
            $bytes = openssl_random_pseudo_bytes(4, $strong);

            if ($bytes === false || !$strong) {
                throw new RuntimeException(
                    'Secure random data is unavailable.'
                );
            }

            $parts = unpack('Nnumber', $bytes);
            $value = $parts['number'];
        } while ($value >= $limit);

        return $min + ($value % $range);
    }
}

// Default values
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

$inputText = '';
$searchFor = '';
$amountText = '';
$amount = 0.0;
$error = '';

if ($isPost) {
    $inputText = trim((string)(
        isset($_POST['text']) ? $_POST['text'] : ''
    ));

    $searchFor = trim((string)(
        isset($_POST['search']) ? $_POST['search'] : ''
    ));

    $amountText = trim((string)(
        isset($_POST['amount']) ? $_POST['amount'] : ''
    ));

    if ($amountText === '' ||
        filter_var($amountText, FILTER_VALIDATE_FLOAT) === false) {
        $error = 'Please enter a valid dollar amount.';
    } else {
        $amount = (float)$amountText;
    }
}

// String functions
$lower = strtolower($inputText);
$upper = strtoupper($inputText);
$trimmed = trim($inputText);
$length = strlen($trimmed);

$contains = false;
$startsWith = false;
$endsWith = false;

if ($searchFor !== '') {
    $contains = str_contains(
        strtolower($trimmed),
        strtolower($searchFor)
    );

    $startsWith = str_starts_with(
        strtolower($trimmed),
        strtolower($searchFor)
    );

    $endsWith = str_ends_with(
        strtolower($trimmed),
        strtolower($searchFor)
    );
}

// Replace vowels with asterisks
$vowelsReplaced = str_replace(
    array('a', 'e', 'i', 'o', 'u',
          'A', 'E', 'I', 'O', 'U'),
    '*',
    $trimmed
);

// First ten characters
$firstTen = substr($trimmed, 0, 10);

// Count words; empty text has zero words
$wordCount = 0;

if ($trimmed !== '') {
    $normalizedText = preg_replace('/\s+/', ' ', $trimmed);
    $wordCount = count(explode(' ', $normalizedText));
}

// Number functions
$formatted = number_format($amount, 2);
$currency = '$' . $formatted;
$rounded = round($amount, 0);
$ceiling = ceil($amount);
$padded = sprintf('%10.2f', $amount);
$randNum = null;

if ($isPost && $error === '') {
    try {
        $randNum = random_int(1, 100);
    } catch (Exception $e) {
        $error = 'Secure random number generation is unavailable.';
    }
}

// Date functions - US Central time
$tz = new DateTimeZone('America/Chicago');

$now = new DateTime('now', $tz);
$today = new DateTime('today', $tz);

$formattedDate = $now->format('l, F j, Y');
$formattedTime = $now->format('g:i:s A T');

$tomorrow = clone $today;
$tomorrow->modify('+1 day');

$firstOfYear = new DateTime(
    $today->format('Y') . '-01-01',
    $tz
);

$daysSinceJanuary = date_diff(
    $firstOfYear,
    $today
)->days;

$christmas = new DateTime(
    $today->format('Y') . '-12-25',
    $tz
);

if ($today > $christmas) {
    $christmas->modify('+1 year');
}

$daysUntilChristmas = date_diff(
    $today,
    $christmas
)->days;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Exercise 17-1 - Strings, Numbers and Dates</title>
</head>

<body>

<h1>String, Number & Date Functions</h1>

<h2>Enter Information</h2>

<form method="post">

    <p>
        <label for="text">Enter Text:</label>
        <input type="text" id="text" name="text" size="40"
            value="<?= htmlspecialchars($inputText, ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <p>
        <label for="search">Search Within Text:</label>
        <input type="text" id="search" name="search"
            value="<?= htmlspecialchars($searchFor, ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <p>
        <label for="amount">Dollar Amount:</label>
        <input type="number" id="amount" name="amount"
               step="0.01"
               value="<?= htmlspecialchars($amountText, ENT_QUOTES, 'UTF-8') ?>"
               required>
    </p>

    <button type="submit">Analyze</button>
</form>

<?php if ($error !== ''): ?>
    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php if ($isPost && $error === ''): ?>

<h2>String Results</h2>

<ul>
    <li>Original:
        <?= htmlspecialchars($inputText, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Lowercase:
        <?= htmlspecialchars($lower, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Uppercase:
        <?= htmlspecialchars($upper, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Length:
        <?= (int)$length ?> characters
    </li>

    <li>Vowels Replaced:
        <?= htmlspecialchars($vowelsReplaced, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>First 10 Characters:
        <?= htmlspecialchars($firstTen, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Word Count:
        <?= (int)$wordCount ?>
    </li>

    <?php if ($searchFor !== ''): ?>

    <li>Contains Search Text:
        <?= $contains ? 'Yes' : 'No' ?>
    </li>

    <li>Starts With Search Text:
        <?= $startsWith ? 'Yes' : 'No' ?>
    </li>

    <li>Ends With Search Text:
        <?= $endsWith ? 'Yes' : 'No' ?>
    </li>

    <?php endif; ?>
</ul>

<h2>Number Results</h2>

<ul>
    <li>Formatted Currency:
        <?= htmlspecialchars($currency, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Rounded:
        <?= number_format($rounded, 0) ?>
    </li>

    <li>Ceiling:
        <?= (int)$ceiling ?>
    </li>

    <li>Random Number (1-100):
        <?= (int)$randNum ?>
    </li>

    <li>Padded to 10 Characters:
        <pre><?= htmlspecialchars($padded, ENT_QUOTES, 'UTF-8') ?></pre>
    </li>
</ul>

<?php endif; ?>

<h2>Date and Time (America/Chicago)</h2>

<ul>
    <li>Today:
        <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Current Time:
        <?= htmlspecialchars($formattedTime, ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Tomorrow:
        <?= htmlspecialchars($tomorrow->format('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>
    </li>

    <li>Days Until Christmas:
        <?= (int)$daysUntilChristmas ?>
    </li>

    <li>Days Since January 1:
        <?= (int)$daysSinceJanuary ?>
    </li>
</ul>

</body>
</html>
