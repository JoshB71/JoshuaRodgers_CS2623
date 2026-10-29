<?php
declare(strict_types=1);

// Determine whether the page is being opened or submitted.
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD') ?? 'GET';
$is_post = $method === 'POST';

// Set default values.
$first = '';
$last = '';
$email = '';
$age = null;
$gender = 'not specified';
$country = '';
$comments = '';
$errors = [];

// Process the form after the Submit button is clicked.
if ($is_post) {

    // Read the submitted information.
    $first = trim((string)(filter_input(INPUT_POST, 'first_name') ?? ''));
    $last = trim((string)(filter_input(INPUT_POST, 'last_name') ?? ''));

    // Validate email and age.
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

    // Read the remaining fields.
    $gender = filter_input(INPUT_POST, 'gender') ?? 'not specified';
    $country = filter_input(INPUT_POST, 'country') ?? '';
    $comments = filter_input(INPUT_POST, 'comments') ?? '';

    // Check required fields.
    if ($first === '') {
        $errors[] = 'First name is required.';
    }

    if ($last === '') {
        $errors[] = 'Last name is required.';
    }

    if ($email === false || $email === null) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($age === false || $age === null || $age < 1 || $age > 120) {
        $errors[] = 'Please enter a valid age between 1 and 120.';
    }

    // Allow only the expected gender values.
    if (!in_array($gender, ['male', 'female', 'other'], true)) {
        $gender = 'not specified';
    }

    // Validate the selected country.
    $countries = [
        'United States',
        'Canada',
        'Mexico',
        'United Kingdom',
        'Japan',
        'Australia'
    ];

    if (!in_array($country, $countries, true)) {
        $errors[] = 'Please select a valid country.';
        $country = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SSP05 Exercise 7-1</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 30px;
        }

        main {
            max-width: 750px;
            margin: 0 auto;
            padding: 25px;
            background-color: white;
            border: 1px solid #ddd;
        }

        h1, h2 {
            color: #1B2A6B;
        }

        .field {
            margin-bottom: 15px;
        }

        .field > label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        fieldset {
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #006B7D;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: #a00000;
        }
    </style>
</head>

<body>
<main>

    <h1>Exercise 7-1: Personal Information Form</h1>

    <!-- This form submits its data back to this same PHP file. -->
    <form method="post">

        <div class="field">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name"
                   name="first_name" required>
        </div>

        <div class="field">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name"
                   name="last_name" required>
        </div>

        <div class="field">
            <label for="email">Email:</label>
            <input type="text" id="email"
                   name="email" required>
        </div>

        <div class="field">
            <label for="age">Age:</label>
            <input type="text" id="age"
                   name="age" required>
        </div>

        <fieldset>
            <legend>Gender:</legend>

            <label>
                <input type="radio" name="gender" value="male">
                Male
            </label>

            <label>
                <input type="radio" name="gender" value="female">
                Female
            </label>

            <label>
                <input type="radio" name="gender" value="other">
                Other
            </label>
        </fieldset>

        <div class="field">
            <label for="country">Country:</label>

            <select id="country" name="country" required>
                <option value="">Select a country</option>
                <option value="United States">United States</option>
                <option value="Canada">Canada</option>
                <option value="Mexico">Mexico</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Japan">Japan</option>
                <option value="Australia">Australia</option>
            </select>
        </div>

        <div class="field">
            <label for="comments">Comments:</label>

            <textarea id="comments" name="comments"
                      rows="5"></textarea>
        </div>

        <button type="submit">Submit Information</button>

    </form>

    <!-- Display results after the form is submitted. -->
    <?php if ($is_post): ?>

        <h2>Submitted Information</h2>

        <?php if (!empty($errors)): ?>
            <h3 class="error">Please review these errors:</h3>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li class="error">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p><strong>Name:</strong>
            <?= htmlspecialchars($first . ' ' . $last, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><strong>Email:</strong>
            <?= htmlspecialchars(
                is_string($email) ? $email : '(invalid)',
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p><strong>Age:</strong>
            <?= htmlspecialchars(
                is_int($age) && $age >= 1 && $age <= 120
                    ? (string)$age
                    : '(invalid)',
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p><strong>Gender:</strong>
            <?= htmlspecialchars($gender, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><strong>Country:</strong>
            <?= htmlspecialchars($country, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><strong>Comments:</strong></p>

        <p>
            <?= nl2br(htmlspecialchars($comments, ENT_QUOTES, 'UTF-8')) ?>
        </p>

    <?php endif; ?>

</main>
</body>
</html>
