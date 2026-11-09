
# CS-2623 Week 4 — Copilot Awareness Log

## SSP07 — Exercise 11-1: Arrays

| Approach reviewed | Action | Reason |
|---|---|---|
| array() syntax | Improved | Used modern [] short array syntax. |
| in_array() without strict comparison | Improved | Added true as the third argument. |
| Displaying array values without escaping | Rejected | Used htmlspecialchars() for safer output. |
| Long anonymous function for filtering | Improved | Used an arrow function with array_filter(). |
| Displaying raw product prices | Improved | Used number_format() for two decimal places. |

## SSP07 — Exercise 15-1: Validation


| Approach reviewed | Action | Reason |
|---|---|---|
| Custom email regex | Rejected | Used filter_var() for email validation. |
| Custom URL regex | Rejected | Used FILTER_VALIDATE_URL instead. |
| Phone and ZIP validation | Accepted | Used preg_match() with specific patterns. |
| Product code validation | Improved | Required two uppercase letters followed by four digits. |
| strip_tags() for output safety | Rejected | Used htmlspecialchars() to escape displayed values. |
| Directly displaying textarea content | Improved | Used nl2br(htmlspecialchars()) to preserve line breaks safely. |
| Generic exception handling | Improved | Created FormatException and caught specific exceptions first. |


## SSP06 — Exercise 21-2: Authentication


| Approach reviewed | Action | Reason |
|---|---|---|
| md5() or sha1() for passwords | Rejected | Used password_hash() and password_verify() for secure password handling. |
| session_start() without regenerating the session ID | Improved | Added session_regenerate_id(true) after login. |
| Directly reading username from POST | Improved | Used validated form handling and htmlspecialchars() for displayed values. |
| Comparing passwords as plain text | Rejected | Used password_verify() with hashed passwords. |
| session_destroy() on logout | Accepted | Cleared the session and redirected to login.php. |


## SSP06 — Exercise 23-1: Shopping Cart


| Approach reviewed | Action | Reason |
|---|---|---|
| Initializing the cart with a longer conditional | Improved | Used $_SESSION['cart'] ??= [] for initialization. |
| Directly casting POST product IDs to integers | Improved | Used filter_input() with FILTER_VALIDATE_INT. |
| Displaying prices without formatting | Improved | Used number_format() for currency formatting. |
| Using session data without starting the session | Improved | Used the authentication file to initialize and protect the session. |
| Allowing unrestricted cart access | Rejected | Required login through auth.php before displaying the cart. |

