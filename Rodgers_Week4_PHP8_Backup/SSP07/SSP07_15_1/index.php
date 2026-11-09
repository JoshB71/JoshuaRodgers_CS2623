<?php
declare(strict_types=1);

// Custom exception for invalid form information
class ValidationException extends RuntimeException {}
class FormatException extends ValidationException {}


// Validate the submitted contact information
function validateContact(array $data): array
{
    $errors = [];

    // Check email address
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }
	
	// Validate website URL
	if (!filter_var($data['website'], FILTER_VALIDATE_URL)) {
		$errors[] = 'Invalid website URL.';
	}


    // Check phone number: 555-555-5555
    if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $data['phone'])) {
        $errors[] = 'Phone must use the format 555-555-5555.';
    }

    // Check ZIP code: 12345 or 12345-6789
    if (!preg_match('/^\d{5}(-\d{4})?$/', $data['zip'])) {
        $errors[] = 'ZIP must contain 5 digits or ZIP+4.';
    }

    
	if ($errors) {
    throw new ValidationException(implode(' ', $errors));
}

	// Validate product code: 2 uppercase letters + 4 digits
	if (!preg_match('/^[A-Z]{2}\d{4}$/', $data['product_code'])) {
    throw new FormatException(
        'Product code must match AB1234.'
    );
}

return $data;


    return $data;
}

$email = '';
$phone = '';
$zip = '';
$notes = '';
$website = '';
$messageText = '';
$productCode = '';


$result = null;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)(filter_input(INPUT_POST, 'email') ?? ''));
    $phone = trim((string)(filter_input(INPUT_POST, 'phone') ?? ''));
    $zip = trim((string)(filter_input(INPUT_POST, 'zip') ?? ''));
    $notes = trim((string)(filter_input(INPUT_POST, 'notes') ?? ''));
	$website = trim((string)(filter_input(INPUT_POST, 'website') ?? ''));
	$messageText = (string)(filter_input(INPUT_POST, 'message') ?? '');
	$productCode = trim((string)(filter_input(INPUT_POST, 'product_code') ?? ''));

	
	$data = [
    'email' => $email,
    'phone' => $phone,
    'zip' => $zip,
    'notes' => $notes,
    'website' => $website,
    'message' => $messageText,
    'product_code' => $productCode
	];


    try {
        $result = validateContact($data);
    } catch (FormatException $e) {
        $errorMessage = $e->getMessage();
    } catch (ValidationException $e) {
        $errorMessage = $e->getMessage();
    } catch (RuntimeException $e) {
        $errorMessage = 'System error: ' . $e->getMessage();
    } finally {
        error_log('Contact form processed.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exercise 15-1 - Validation</title>
</head>
<body>

<h1>Contact Form Validation</h1>

<?php if ($errorMessage !== ''): ?>
    <p>
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if ($result !== null): ?>
    <h2>Validation Successful!</h2>
	
	<h3>Your Message</h3>
	<p>
    <?= nl2br(htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8')) ?>
	</p>
	
    <p>Your information is valid.</p>

    <p>Email:
        <?= htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>Phone:
        <?= htmlspecialchars($result['phone'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>ZIP:
        <?= htmlspecialchars($result['zip'], ENT_QUOTES, 'UTF-8') ?>
    </p>
	
<?php endif; ?>

<form method="post">

    <p>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email"
               value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <p>
        <label for="phone">Phone (555-555-5555):</label>
        <input type="text" id="phone" name="phone"
               value="<?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') ?>">
    </p>

    <p>
        <label for="zip">ZIP Code:</label>
        <input type="text" id="zip" name="zip"
               value="<?= htmlspecialchars($zip, ENT_QUOTES, 'UTF-8') ?>">
    </p>
	
	<p>
    <label for="website">Website URL:</label>
    <input type="text" id="website" name="website"
           value="<?= htmlspecialchars($website, ENT_QUOTES, 'UTF-8') ?>">
	</p>

    <p>
        <label for="notes">Notes:</label>
        <textarea id="notes" name="notes" rows="4" cols="40"><?= htmlspecialchars($notes, ENT_QUOTES, 'UTF-8') ?></textarea>
	</p>
	
	<p>
    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="5" cols="40"><?= htmlspecialchars($messageText, ENT_QUOTES, 'UTF-8') ?></textarea>
	</p>

    <p>
        <label for="product_code">Product Code (AB1234):</label>
        <input type="text" id="product_code" name="product_code">
    </p>

    <button type="submit">Validate</button>

</form>

</body>
</html>
