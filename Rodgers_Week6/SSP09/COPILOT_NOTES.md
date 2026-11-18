# CS2623 Week 6 — Frosty Game Code Review Notes

## Accepted

- **session_start():** Used to keep the game information saved between page loads.
- **str_split():** Used to turn the secret word into an array of individual letters.
- **str_repeat():** Used to create underscores for the hidden word.
- **str_contains():** Used to check if a letter is in the word or has already been guessed.
- **Type declarations:** Used in the functions to make the expected data types clearer.

## Improved

- **Letter input:** Instead of accepting the entered letter without checking it, used `strtoupper(trim())` and `ctype_alpha()` to make sure it is a single letter.
- **Date and time:** Instead of using `date()` without setting the timezone, used `DateTime` with `DateTimeZone('America/Chicago')`.
- **Displaying information:** Used `htmlspecialchars()` when displaying strings to help prevent unsafe HTML from being displayed.
- **Letter searching:** Used `str_contains()` instead of a basic `strpos()` condition to avoid problems when a letter is at position zero.

## Rejected

- **Directly using $_POST for guesses:** Used `filter_input()` instead to read submitted information.
- **Using match instead of if/elseif/else:** Kept the conditional structure required by the assignment.

## Testing

Tested the Frosty game locally using XAMPP. Correct guesses revealed letters, wrong guesses advanced the snowman images, repeated guesses counted as incorrect, and the restart and win features worked.