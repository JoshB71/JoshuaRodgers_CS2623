# CS-2623 Week 5 — GitHub Copilot Awareness Log

This log records suggested coding approaches reviewed during the exercises, including accepted, improved, and rejected alternatives.

## SSP08 — Exercise 03-1: Control Structures

| Approach Reviewed | Action | Reason |
|---|---|---|
| Use match() for day names | Rejected | The assignment requires if/elseif statements. |
| Use match() for letter grades | Rejected | The assignment requires a switch statement. |
| Use type-hinted functions | Accepted | PHP 8 parameter and return types improve clarity. |
| Use an incorrect upper loop boundary | Improved | The for loop uses $i <= $end. |
| Echo directly inside functions | Improved | Functions return values, and the page displays them using htmlspecialchars(). |

## SSP08 — Exercise 16-1: File I/O

| Approach Reviewed | Action | Reason |
|---|---|---|
| Use date() without specifying a timezone | Improved | Used DateTime and DateTimeZone('America/Chicago'). |
| Use fopen() and fgets() to read a small file | Improved | Used file_get_contents() with a file_exists() check. |
| Build file paths from user input | Rejected | Used fixed paths and basename() when displaying filenames. |
| Write files without LOCK_EX | Improved | Used FILE_APPEND and LOCK_EX to protect writes. |
| Display all scandir() results | Improved | Used array_filter() with is_file() to display actual files. |
| Make the log file world-writable | Rejected | Used file permissions of 0644. |

## SSP09 — Exercise 17-1: Strings, Numbers & Dates

| Approach Reviewed | Action | Reason |
|---|---|---|
| Use rand() for random numbers | Improved | Used random_int() for secure random number generation. |
| Use str_contains() | Accepted | Used the PHP 8 function, with a compatibility fallback for Apollo. |
| Use date() without a timezone | Improved | Used DateTime and DateTimeZone('America/Chicago'). |
| Use strpos() for every string search | Improved | Used str_contains(), str_starts_with(), and str_ends_with(). |
| Display currency without formatting | Improved | Used number_format() with two decimal places. |
| Display raw user input | Rejected | Used htmlspecialchars() to safely display form values. |

## PHP Version Compatibility

Local development uses PHP 8.5.12.
Apollo uses PHP 5.5.12.

The required PHP 8 code is preserved for local testing and GitHub.
Apollo requires a compatibility version for unsupported PHP 8 syntax.
