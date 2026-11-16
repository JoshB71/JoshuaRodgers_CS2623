<?php

// SSP08 - Exercise 03-1
// Control Structures

// FizzBuzz function using if / elseif / else
function fn_fizzbuzz($n)
{
    if ($n % 15 === 0) {
        return 'FizzBuzz';
    } elseif ($n % 3 === 0) {
        return 'Fizz';
    } elseif ($n % 5 === 0) {
        return 'Buzz';
    } else {
        return (string)$n;
    }
}

// Day of the week using if / elseif
function fn_dayOfWeek($dow)
{
    if ($dow === 0) {
        return 'Sunday';
    } elseif ($dow === 1) {
        return 'Monday';
    } elseif ($dow === 2) {
        return 'Tuesday';
    } elseif ($dow === 3) {
        return 'Wednesday';
    } elseif ($dow === 4) {
        return 'Thursday';
    } elseif ($dow === 5) {
        return 'Friday';
    } else {
        return 'Saturday';
    }
}

// Letter grade using switch
function fn_gradeLabel($score)
{
    switch (true) {
        case $score >= 90:
            return 'A';

        case $score >= 80:
            return 'B';

        case $score >= 70:
            return 'C';

        case $score >= 60:
            return 'D';

        default:
            return 'F';
    }
}

// Default values
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

$start = 1;
$end = 20;
$score = null;
$repeatWord = '';
$repeatCount = 3;
$errors = [];

// Process submitted form
if ($isPost) {

    $startInput = filter_input(
        INPUT_POST,
        'start',
        FILTER_VALIDATE_INT
    );

    $endInput = filter_input(
        INPUT_POST,
        'end',
        FILTER_VALIDATE_INT
    );

    $scoreInput = filter_input(
        INPUT_POST,
        'score',
        FILTER_VALIDATE_INT
    );

    $countInput = filter_input(
        INPUT_POST,
        'repeat_count',
        FILTER_VALIDATE_INT
    );

    
	$scoreText = trim((string)(isset($_POST['score']) ? $_POST['score'] : ''));


    
	$repeatWord = trim(
    (string)(isset($_POST['repeat_word']) ? $_POST['repeat_word'] : '')
	);


    // Validate starting number
    if (!is_int($startInput) ||
        $startInput < 1 ||
        $startInput > 100) {

        $errors[] = 'Start must be between 1 and 100.';

    } else {
        $start = $startInput;
    }

    // Validate ending number
    if (!is_int($endInput) ||
        $endInput < 1 ||
        $endInput > 100) {

        $errors[] = 'End must be between 1 and 100.';

    } else {
        $end = $endInput;
    }

    // Start cannot be greater than End
    if (is_int($startInput) &&
        is_int($endInput) &&
        $start > $end) {

        $errors[] = 'Error: Start cannot be greater than End.';
    }

    // Validate optional score
    if ($scoreText !== '') {

        if (!is_int($scoreInput) ||
            $scoreInput < 0 ||
            $scoreInput > 100) {

            $errors[] = 'Score must be between 0 and 100.';

        } else {
            $score = $scoreInput;
        }
    }

    // Validate repeat count
    if (!is_int($countInput) ||
        $countInput < 1 ||
        $countInput > 20) {

        $errors[] = 'Repeat count must be between 1 and 20.';

    } else {
        $repeatCount = $countInput;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Exercise 03-1 - Control Structures</title>

    <style>
        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>

<h1>Control Structures Demo</h1>

<h2>Enter Your Information</h2>

<form method="post">

    <p>
        <label for="start">Start:</label>

        <input type="number"
               id="start"
               name="start"
               min="1"
               max="100"
               value="<?= (int)$start ?>"
               required>
    </p>

    <p>
        <label for="end">End:</label>

        <input type="number"
               id="end"
               name="end"
               min="1"
               max="100"
               value="<?= (int)$end ?>"
               required>
    </p>

    <p>
        <label for="score">Score (0-100):</label>

        <input type="number"
               id="score"
               name="score"
               min="0"
               max="100"
               value="<?= $score === null ? '' : (int)$score ?>">
    </p>

    <p>
        <label for="repeat_word">Repeat Word:</label>

        <input type="text"
               id="repeat_word"
               name="repeat_word"
               value="<?= htmlspecialchars($repeatWord, ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <p>
        <label for="repeat_count">Repeat How Many Times?</label>

        <input type="number"
               id="repeat_count"
               name="repeat_count"
               min="1"
               max="20"
               value="<?= (int)$repeatCount ?>"
               required>
    </p>

    <button type="submit">Run</button>

</form>

<?php if (!empty($errors)): ?>

    <h2>Validation Errors</h2>

    <ul>
        <?php foreach ($errors as $error): ?>
            <li>
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php elseif ($isPost): ?>

    <!-- FOR LOOP -->
    <h2>FizzBuzz - For Loop</h2>

    <ul>
        <?php for ($i = $start; $i <= $end; $i++): ?>

            <li>
                <?= (int)$i ?>:
                <?= htmlspecialchars(fn_fizzbuzz($i), ENT_QUOTES, 'UTF-8') ?>
            </li>

        <?php endfor; ?>
    </ul>


    <!-- WHILE LOOP -->
    <h2>Days of the Week - While Loop</h2>

    <ul>
        <?php $day = 0; ?>

        <?php while ($day <= 6): ?>

            <li>
                <?= htmlspecialchars(fn_dayOfWeek($day), ENT_QUOTES, 'UTF-8') ?>
            </li>

            <?php $day++; ?>

        <?php endwhile; ?>
    </ul>


    <!-- SWITCH STATEMENT -->
    <h2>Grade Lookup - Switch Statement</h2>

    <?php if ($score !== null): ?>

        <p>
            Score: <?= (int)$score ?>

            - Grade:

            <strong>
                <?= htmlspecialchars(fn_gradeLabel($score), ENT_QUOTES, 'UTF-8') ?>
            </strong>
        </p>

    <?php else: ?>

        <p>No score entered.</p>

    <?php endif; ?>


    <!-- DO-WHILE MULTIPLICATION TABLE -->
    <h2>Multiplication Table - Do-While Loop</h2>

    <table>
        <tbody>

        <?php $row = 1; ?>

        <?php do { ?>

            <tr>

                <?php $col = 1; ?>

                <?php do { ?>

                    <td>
                        <?= (int)($row * $col) ?>
                    </td>

                    <?php $col++; ?>

                <?php } while ($col <= 5); ?>

            </tr>

            <?php $row++; ?>

        <?php } while ($row <= 5); ?>

        </tbody>
    </table>


    <!-- COUNTDOWN DO-WHILE LOOP -->
    <h2>Count Down - Do-While Loop</h2>

    <ul>

        <?php $number = $end; ?>

        <?php do { ?>

            <li><?= (int)$number ?></li>

            <?php $number--; ?>

        <?php } while ($number >= $start); ?>

    </ul>


    <!-- REPEAT WORD WHILE LOOP -->
    <h2>Repeat Word - While Loop</h2>

    <?php if ($repeatWord !== ''): ?>

        <ul>

            <?php $counter = 0; ?>

            <?php while ($counter < $repeatCount): ?>

                <li>
                    <?= htmlspecialchars($repeatWord, ENT_QUOTES, 'UTF-8') ?>
                </li>

                <?php $counter++; ?>

            <?php endwhile; ?>

        </ul>

    <?php else: ?>

        <p>No word entered.</p>

    <?php endif; ?>

<?php endif; ?>

</body>
</html>
