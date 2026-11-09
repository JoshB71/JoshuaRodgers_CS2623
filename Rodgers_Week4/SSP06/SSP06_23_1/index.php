<?php

// Require login before accessing the shopping cart
require __DIR__ . '/../SSP06_21_2/auth.php';

// Product catalog
$products = [
    1 => ['id' => 1, 'name' => 'Fender Stratocaster', 'price' => 499.99],
    2 => ['id' => 2, 'name' => 'Gibson Les Paul', 'price' => 799.99],
    3 => ['id' => 3, 'name' => 'Martin D-28 Acoustic', 'price' => 1299.99],
    4 => ['id' => 4, 'name' => 'Ibanez RG550', 'price' => 299.99]
];

// Initialize the session cart
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

// Handle Add and Remove buttons
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $action = (string)(isset($_POST['action']) ? $_POST['action'] : '');

    if ($id !== false && $id !== null && isset($products[$id])) {

        if ($action === 'add') {
            $_SESSION['cart'][$id] =
                (isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id] : 0) + 1;
        }

        if ($action === 'remove' && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]--;

            if ($_SESSION['cart'][$id] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    header('Location: index.php');
    exit;
}

// Calculate number of items and total price
$itemCount = array_sum($_SESSION['cart']);
$total = 0.0;

foreach ($_SESSION['cart'] as $id => $quantity) {
    if (isset($products[$id])) {
        $total += $products[$id]['price'] * $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSP06 - Shopping Cart</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        .product {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Guitar Shop</h1>
    <p>
        Welcome,
        <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>
        | Cart (<?= (int)$itemCount ?> items)
    </p>
</header>

<nav>
    <a href="../SSP06_21_2/dashboard.php">Dashboard</a>
    |
    <a href="../SSP06_21_2/logout.php">Log Out</a>
</nav>

<h2>Product Catalog</h2>

<?php foreach ($products as $product): ?>
    <div class="product">
        <h3>
            <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
        </h3>

        <p>
            $<?= number_format($product['price'], 2) ?>
        </p>

        <form method="post">
            <input type="hidden" name="product_id"
                   value="<?= (int)$product['id'] ?>">

            <button type="submit" name="action" value="add">
                Add to Cart
            </button>
        </form>
    </div>
<?php endforeach; ?>

<h2>Your Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty.</p>
<?php else: ?>

    <table>
        <thead>
            <tr>
                <th scope="col">Product</th>
                <th scope="col">Quantity</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($_SESSION['cart'] as $id => $quantity): ?>
            <?php if (isset($products[$id])): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $products[$id]['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>

                    <td><?= (int)$quantity ?></td>

                    <td>
                        $<?= number_format(
                            $products[$id]['price'] * $quantity,
                            2
                        ) ?>
                    </td>

                    <td>
                        <form method="post">
                            <input type="hidden" name="product_id"
                                   value="<?= (int)$id ?>">

                            <button type="submit"
                                    name="action"
                                    value="remove">
                                Remove One
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr>
            <th colspan="2" scope="row">Grand Total</th>
            <td>$<?= number_format($total, 2) ?></td>
            <td></td>
        </tr>

        </tbody>
    </table>

    <p>
        <a href="cart_clear.php">Clear Cart</a>
    </p>

<?php endif; ?>

</body>
</html>
