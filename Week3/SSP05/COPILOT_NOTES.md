# Week 3 - AI Assistance Notes
## CS-2623 PHP Programming - SSP05

AI assistance used: ChatGPT

I used ChatGPT to help understand the assignment, build the
PHP forms, troubleshoot compatibility issues, and test the
completed pages. I reviewed the suggestions against the
assignment requirements and tested the results myself.

## Chapter 6 - Testing and Debugging

### Entry 1 - Testing on XAMPP and Apollo
Suggestion: Test the PHP forms locally using XAMPP, then test
them again on the Apollo server.

Decision: ACCEPTED

Reason: Code that works on my computer might not work on
Apollo because the servers use different PHP versions.

Result: Both forms worked locally. I also tested them on
Apollo using valid and invalid information.


### Entry 2 - PHP Version Compatibility
Suggestion: Adjust the code for Apollo's older PHP version.

Decision: IMPROVED

Reason: The original Week 3 code uses newer PHP features
required by the assignment, including strict_types.
Apollo does not support all of these features.

Result: I kept the original PHP 8 assignment files and
used a separate compatible copy for Apollo. This allowed
me to test the website without replacing the original code.


### Entry 3 - Debugging Output
Source: Chapter 6 course notes demonstrated using echo
statements to trace PHP execution.

Decision: REJECTED for the final published code

Reason: Temporary debugging messages can help find errors,
but they should not appear in the completed website.

Result: I tested the forms using valid and invalid input
and did not leave temporary debugging messages in the pages.


## Chapter 7 - Working with Form Data

### Entry 4 - Reading and Validating Form Data
Suggestion: Use filter_input() to retrieve form values and
use FILTER_VALIDATE_EMAIL and FILTER_VALIDATE_INT to
validate the email address and age.

Decision: ACCEPTED

Reason: The assignment specifically requires filter_input().
Validation also helps prevent incorrect information from
being accepted.

Result: I tested Exercise 7-1 with both valid and invalid
email addresses and ages. The validation messages worked.


### Entry 5 - Checkbox Arrays
Suggestion: Use options[] for the order form checkboxes,
FILTER_REQUIRE_ARRAY to retrieve them, and handle the
case where no options are selected.

Decision: ACCEPTED

Reason: When no checkboxes are selected, the form might
not send an options value. The program needs to handle
that case without PHP errors.

Result: Exercise 7-2 correctly processed selected add-ons.
I also tested an order with no add-ons. Two wireless mice
produced the correct total of $39.98.


## Chapter 21 - Website Security

### Entry 6 - Protecting Displayed Information
Suggestion: Use htmlspecialchars() with ENT_QUOTES and
UTF-8 when displaying form information. Use nl2br()
after htmlspecialchars() for textarea content.

Decision: ACCEPTED

Reason: Escaping displayed text helps prevent user input
from being interpreted as HTML or JavaScript.

Result: Both exercises escape submitted information before
displaying it. Comments and special instructions also
preserve line breaks.


## Final Testing Summary

- Week 3 homepage opened successfully on Apache/XAMPP.
- Both exercise links worked.
- Exercise 7-1 accepted valid information.
- Exercise 7-1 displayed errors for invalid email and age.
- Exercise 7-2 calculated totals with selected add-ons.
- Exercise 7-2 worked when no add-ons were selected.
- Both forms were tested on the Apollo server.
- No visible PHP errors remained during testing.
