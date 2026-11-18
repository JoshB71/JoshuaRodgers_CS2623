<?php

// CS2623 - Week 6
// SSP09 - Frosty Word Guessing Game

session_start();

require_once __DIR__ . '/functions/functions_SSP09.php';

// Use Central Time for the game timestamps.
$timezone = new DateTimeZone('America/Chicago');

// Restart the game when requested.
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['restart_game'])) {
    unset($_SESSION['secretWord']);
}

// Initialize all 12 game variables.
if (!isset($_SESSION['secretWord'])) {

    $now = new DateTime('now', $timezone);
    $timestamp = $now->format('Y-m-d H:i:s');

    $_SESSION['secretWord'] = 'BLIZZARD';
    $_SESSION['secretWord_hint'] = 'A winter weather event';

    $_SESSION['secretWord_array'] =
        str_split(strtoupper($_SESSION['secretWord']));

    $_SESSION['secretWord_len'] =
        strlen($_SESSION['secretWord']);

    $_SESSION['guess_secretWord'] =
        str_repeat('_', $_SESSION['secretWord_len']);

    $_SESSION['guess_count'] = 0;
    $_SESSION['guess_count_wrong'] = 0;
    $_SESSION['guess_tracked'] = '';
    $_SESSION['guess_image'] = 0;
    $_SESSION['guess_letter'] = '';

    $_SESSION['game_lastGuess'] = $timestamp;
    $_SESSION['game_started'] = $timestamp;
}

// Load the stored session information.
$secretWord = $_SESSION['secretWord'];
$secretWord_hint = $_SESSION['secretWord_hint'];
$secretWord_array = $_SESSION['secretWord_array'];
$secretWord_len = $_SESSION['secretWord_len'];

$guess_secretWord = $_SESSION['guess_secretWord'];
$guess_count = $_SESSION['guess_count'];
$guess_count_wrong = $_SESSION['guess_count_wrong'];
$guess_tracked = $_SESSION['guess_tracked'];
$guess_image = $_SESSION['guess_image'];
$guess_letter = $_SESSION['guess_letter'];

$game_lastGuess = $_SESSION['game_lastGuess'];
$game_started = $_SESSION['game_started'];

$error = '';

// Process a letter submitted by the player.
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && !isset($_POST['restart_game'])) {

    $raw = filter_input(
        INPUT_POST,
        'frosty_letter_guess',
        FILTER_UNSAFE_RAW
    );

    $guess_letter = strtoupper(trim(is_string($raw) ? $raw : ''));

    // Only accept a single alphabetic letter.
    if (strlen($guess_letter) !== 1
        || !ctype_alpha($guess_letter)) {

        $error = 'Please enter one letter from A to Z.';

    } elseif ($guess_secretWord === $secretWord
        || $guess_image >= 6) {

        $error = 'The game is over. Start a new game.';

    } else {
		ob_start();
        frosty_guessing(
            $secretWord,
            $secretWord_hint,
            $guess_secretWord,
            $guess_letter,
            $guess_tracked,
            $guess_count,
            $guess_count_wrong,
            $game_started,
            $game_lastGuess,
            $secretWord_len,
            $secretWord_array,
            $guess_image
        );
	$roundHtml = ob_get_clean();
    }
}

// Refresh values after processing a guess.
$guess_secretWord = $_SESSION['guess_secretWord'];
$guess_count = $_SESSION['guess_count'];
$guess_count_wrong = $_SESSION['guess_count_wrong'];
$guess_tracked = $_SESSION['guess_tracked'];
$guess_image = $_SESSION['guess_image'];
$game_lastGuess = $_SESSION['game_lastGuess'];

$gameWon = ($guess_secretWord === $secretWord);
$gameLost = (!$gameWon && $guess_image >= 6);
$gameOver = $gameWon || $gameLost;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SSP09 - Frosty Word Guessing Game</title>
</head>

<body>

<h1>Frosty Word Guessing Game</h1>

<p>
    Guess the hidden winter word one letter at a time.
    Each wrong guess brings Frosty closer to completion!
</p>

<?php if ($error !== ''): ?>
    <p>
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if ($gameWon): ?>

    <h2>Congratulations! You won!</h2>

    <p>
        The word was
        <strong>
            <?= htmlspecialchars($secretWord, ENT_QUOTES, 'UTF-8') ?>
        </strong>.
    </p>

<?php elseif ($gameLost): ?>

    <h2>Game Over!</h2>

    <p>
        You ran out of guesses. The word was
        <strong>
            <?= htmlspecialchars($secretWord, ENT_QUOTES, 'UTF-8') ?>
        </strong>.
    </p>

<?php else: ?>

    <h2>Enter Your Guess</h2>

    <form method="post" action="SSP09.php">

        <p>
            <label for="frosty_letter_guess">
                Guess a Letter:
            </label>

            <input
                type="text"
                id="frosty_letter_guess"
                name="frosty_letter_guess"
                maxlength="1"
                pattern="[A-Za-z]"
                required
                autocomplete="off"
            >
        </p>

        <button type="submit">Guess</button>

    </form>

<?php endif; ?>

<h2>Current Game</h2>


<?php
if (isset($roundHtml)) {
    echo $roundHtml;
} else {
    frosty_round(
        $game_started,
        $game_lastGuess,
        $secretWord_len,
        $secretWord_hint,
        $guess_count,
        $guess_count_wrong,
        $guess_tracked,
        $guess_image,
        $guess_secretWord
    );
}
?>


<h2>Restart</h2>

<form method="post" action="SSP09.php">
    <button type="submit" name="restart_game" value="1">
        Start New Game
    </button>
</form>

</body>
</html>
