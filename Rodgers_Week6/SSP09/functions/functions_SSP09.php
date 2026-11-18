
<?php
// CS2623 Week 6 - Frosty Game Functions
// Compatible with PHP 5.5 and newer.

// PHP 5.5 does not include str_contains().
// Provide the same functionality on older servers.
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle === ''
            || strpos($haystack, $needle) !== false;
    }
}

// Process a single letter guess.
function frosty_guessing(
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
) {

    $guess_found = 'N';

    $now = new DateTime(
        'now',
        new DateTimeZone('America/Chicago')
    );
    $game_lastGuess = $now->format('Y-m-d H:i:s');

    // Count each submitted guess.
    $guess_count++;

    // Case 1: Repeated letter.
    if (str_contains($guess_tracked, $guess_letter)) {

        $guess_count_wrong++;
        $guess_image++;

    // Case 2: Already completed word.
    } elseif (
        str_replace('_', '', $guess_secretWord) === $secretWord
    ) {

        // The game is already complete.

    // Case 3: Incorrect letter.
    } elseif (!str_contains($secretWord, $guess_letter)) {

        $guess_tracked .= $guess_letter;
        $guess_count_wrong++;
        $guess_image++;

    // Case 4: Correct letter.
    } else {

        $guess_found = 'Y';
        $guess_tracked .= $guess_letter;

        // Reveal all matching letters.
        for ($i = 0; $i < $secretWord_len; $i++) {

            if ($secretWord_array[$i] === $guess_letter) {

                if ($i === 0) {

                    $guess_secretWord =
                        $guess_letter .
                        substr($guess_secretWord, 1);

                } else {

                    $guess_secretWord =
                        substr($guess_secretWord, 0, $i) .
                        $guess_letter .
                        substr($guess_secretWord, $i + 1);
                }
            }
        }
    }

    // Keep image numbers between 0 and 6.
    $guess_image = max(0, min(6, $guess_image));

    // Save the updated values.
    $_SESSION['guess_secretWord'] = $guess_secretWord;
    $_SESSION['guess_count'] = $guess_count;
    $_SESSION['guess_count_wrong'] = $guess_count_wrong;
    $_SESSION['guess_tracked'] = $guess_tracked;
    $_SESSION['guess_image'] = $guess_image;
    $_SESSION['guess_letter'] = $guess_letter;
    $_SESSION['game_lastGuess'] = $game_lastGuess;

    // Required: display the updated round
    // from inside the guessing function.
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


// Display the game's current status.
function frosty_round(
    $game_started,
    $game_lastGuess,
    $secretWord_len,
    $secretWord_hint,
    $guess_count,
    $guess_count_wrong,
    $guess_tracked,
    $guess_image,
    $guess_secretWord
) {

    $right_count = strlen(
        str_replace('_', '', $guess_secretWord)
    );

    $displayWord = implode(
        ' ',
        str_split($guess_secretWord)
    );

    $imageNumber = max(0, min(6, $guess_image));

    $imagePath =
        'images/frosty' . $imageNumber . '.png';

    echo '<div class="game-status">';

    echo '<p><strong>Game Started:</strong> ' .
        htmlspecialchars(
            $game_started, ENT_QUOTES, 'UTF-8'
        ) .
        '</p>';

    echo '<p><strong>Last Guess:</strong> ' .
        htmlspecialchars(
            $game_lastGuess, ENT_QUOTES, 'UTF-8'
        ) .
        '</p>';

    echo '<hr>';

    echo '<p><strong>Secret Word Hint:</strong> ' .
        htmlspecialchars(
            $secretWord_hint, ENT_QUOTES, 'UTF-8'
        ) .
        '</p>';

    echo '<p><strong>Secret Word Length:</strong> ' .
        (int)$secretWord_len .
        '</p>';

    echo '<p><strong>Current Word:</strong> ' .
        htmlspecialchars(
            $displayWord, ENT_QUOTES, 'UTF-8'
        ) .
        '</p>';

    echo '<p><strong>Total Guesses:</strong> ' .
        (int)$guess_count .
        '</p>';

    echo '<p><strong>Wrong Guesses:</strong> ' .
        (int)$guess_count_wrong .
        '</p>';

    echo '<p><strong>Right Answers:</strong> ' .
        (int)$right_count .
        '</p>';

    echo '<p><strong>Guessed Letters:</strong> ' .
        htmlspecialchars(
            $guess_tracked, ENT_QUOTES, 'UTF-8'
        ) .
        '</p>';

    echo '<h3>Frosty Progress</h3>';

    echo '<p>Frosty Stage: ' .
        (int)$imageNumber .
        ' of 6</p>';

    echo '<img src="' .
        htmlspecialchars(
            $imagePath, ENT_QUOTES, 'UTF-8'
        ) .
        '" alt="Frosty snowman stage ' .
        (int)$imageNumber .
        '" width="250" height="300">';

    echo '</div>';
}
