<?php
declare(strict_types=1);

// Detect whether the form has been submitted.
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD') ?? 'GET';
$is_post = $method === 'POST';

// Available products and prices.
$products = [
    'notebook' => ['name' => 'Notebook', 'price' => 12.99],
    'keyboard' => ['name' => 'Keyboard', 'price' => 24.99],
    'mouse' => ['name' => 'Wireless Mouse', 'price' => 19.99],
    'headphones' => ['name' => 'Headphones', 'price' => 39.99]
];

// Optional add-ons.
$addons = [
    'gift_wrap' => ['name' => 'Gift Wrapping', 'price' => 3.00],
    'express' => ['name' => 'Express Shipping', 'price' => 8.00],
    'insurance' => ['name' => 'Shipping Insurance', 'price' => 5.00]
];

// Default values.
$cust_name = '';
$product = '';
$quantity = null;
$options = [];
$instructions = '';
$payment = 'none';
$errors = [];
$total = 0.00;

// Process the submitted form.
if ($is_post) {

    // Read customer and product information.
    $cust_name = trim((string)(filter_input(
        INPUT_POST, 'cust_name'
    ) ?? ''));

    $product = (string)(filter_input(
        INPUT_POST, 'product'
    ) ?? '');

    // Quantity must be a whole number.
    $quantity = filter_input(
        INPUT_POST, 'quantity', FILTER_VALIDATE_INT
    );

    // Read payment method and special instructions.
    $payment = (string)(filter_input(
        INPUT_POST, 'payment'
    ) ?? 'none');

    $instructions = (string)(filter_input(
        INPUT_POST, 'instructions'
    ) ?? '');

    // Read all selected checkboxes as an array.
    $options = filter_input(
        INPUT_POST,
        'options',
        FILTER_SANITIZE_SPECIAL_CHARS,
        FILTER_REQUIRE_ARRAY
    ) ?? [];

    // Make sure options is always an array.
    if (!is_array($options)) {
        $options = [];
    }

    // Keep only recognized add-on values.
    $options = array_values(
        array_intersect($options, array_keys($addons))
    );

    // Validate customer name.
    if ($cust_name === '') {
        $errors[] = 'Customer name is required.';
    }

    // Validate product selection.
    if (!isset($products[$product])) {
        $errors[] = 'Please select a valid product.';
    }

    // Validate quantity.
    if (!is_int($quantity) || $quantity < 1 || $quantity > 100) {
        $errors[] = 'Quantity must be a whole number between 1 and 100.';
    }

    // Validate payment method.
    if (!in_array($payment, ['credit', 'debit', 'paypal'], true)) {
        $errors[] = 'Please select a valid payment method.';
    }

    // Calculate the order total if the input is valid.
    if (empty($errors)) {

        $total = $products[$product]['price'] * $quantity;

        foreach ($options as $option) {
            $total += $addons[$option]['price'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SSP05 Exercise 7-2</title>

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

        fieldset label {
            display: block;
            margin: 8px 0;
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

    <h1>Exercise 7-2: Product Order Form</h1>

    <!-- The form submits its data to this same PHP page. -->
    <form method="post">

        <div class="field">
            <label for="cust_name">Customer Name:</label>

            <input type="text" id="cust_name"
                   name="cust_name" required>
        </div>

        <div class="field">
            <label for="product">Select Product:</label>

            <select id="product" name="product" required>
                <option value="">Choose a product</option>
                <option value="notebook">Notebook - $12.99</option>
                <option value="keyboard">Keyboard - $24.99</option>
                <option value="mouse">Wireless Mouse - $19.99</option>
                <option value="headphones">Headphones - $39.99</option>
            </select>
        </div>

        <div class="field">
            <label for="quantity">Quantity:</label>

            <input type="text" id="quantity"
                   name="quantity" required>
        </div>

        <fieldset>
            <legend>Optional Add-ons:</legend>

            <label>
                <input type="checkbox"
                       name="options[]" value="gift_wrap">
                Gift Wrapping - $3.00
            </label>

            <label>
                <input type="checkbox"
                       name="options[]" value="express">
                Express Shipping - $8.00
            </label>

            <label>
                <input type="checkbox"
                       name="options[]" value="insurance">
                Shipping Insurance - $5.00
            </label>
        </fieldset>

        <div class="field">
            <label for="instructions">Special Instructions:</label>

            <textarea id="instructions"
                      name="instructions"
                      rows="5"></textarea>
        </div>

        <fieldset>
            <legend>Payment Method:</legend>

            <label>
                <input type="radio"
                       name="payment" value="credit" required>
                Credit Card
            </label>

            <label>
                <input type="radio"
                       name="payment" value="debit">
                Debit Card
            </label>

            <label>
                <input type="radio"
                       name="payment" value="paypal">
                PayPal
            </label>
        </fieldset>

        <button type="submit">Submit Order</button>

    </form>

    <!-- Display the order summary after submission. -->
    <?php if ($is_post): ?>

        <h2>Order Summary</h2>

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

        <p><strong>Customer:</strong>
            <?= htmlspecialchars($cust_name, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><strong>Product:</strong>
            <?= htmlspecialchars(
                isset($products[$product])
                    ? $products[$product]['name']
                    : '(invalid)',
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p><strong>Quantity:</strong>
            <?= htmlspecialchars(
                is_int($quantity) && $quantity >= 1
                    ? (string)$quantity
                    : '(invalid)',
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p><strong>Payment:</strong>
            <?= htmlspecialchars($payment, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><strong>Selected Options:</strong></p>

        <ul>
            <?php if (empty($options)): ?>

                <li>No add-ons selected</li>

            <?php else: ?>

                <?php foreach ($options as $option): ?>
                    <li>
                        <?= htmlspecialchars(
                            $addons[$option]['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </li>
                <?php endforeach; ?>

            <?php endif; ?>
        </ul>

        <p><strong>Special Instructions:</strong></p>

        <p>
            <?= nl2br(htmlspecialchars(
                $instructions, ENT_QUOTES, 'UTF-8'
            )) ?>
        </p>

        <?php if (empty($errors)): ?>

            <h2>
                Order Total:
                <?= htmlspecialchars(
                    '$' . number_format($total, 2),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

        <?php endif; ?>

    <?php endif; ?>

</main>
</body>
</html>
