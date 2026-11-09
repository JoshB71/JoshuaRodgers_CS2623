<?php
declare(strict_types=1);

$fruits = ['cherry', 'apple', 'elderberry', 'banana', 'date'];

$search = trim((string)(filter_input(INPUT_POST, 'search') ?? ''));
$message = '';
$sortRequested = ($_POST['action'] ?? '') === 'sort';

if ($sortRequested) {
    sort($fruits, SORT_STRING);
}

$filteredFruits = array_filter(
    $fruits,
    fn($fruit) => strlen($fruit) >= 6
);

$upperFruits = array_map(
    fn($fruit) => strtoupper($fruit),
    $fruits
);


$catalog = [
    ['id' => 1, 'name' => 'Apple', 'price' => 1.29],
    ['id' => 2, 'name' => 'Banana', 'price' => 0.49],
    ['id' => 3, 'name' => 'Cherry', 'price' => 2.99],
    ['id' => 4, 'name' => 'Dragon Fruit', 'price' => 4.50]
];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$sortRequested) {
    if ($search === '') {
        $message = 'Please enter a fruit.';
    } elseif (!preg_match('/^[a-zA-Z]+$/', $search)) {
        $message = 'Error: Only letters are allowed.';
    } elseif (in_array(strtolower($search), $fruits, true)) {
        $message = 'Found: ' . $search;
    } else {
        $message = 'Not found: ' . $search;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exercise 11-1 - Arrays</title>
</head>
<body>

<h1>Fruit Array Demo</h1>

<h2>Search for a Fruit</h2>

<form method="post">
    <label for="search">Fruit name:</label>
    <input type="text" id="search" name="search"
           value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit">Search</button>
	<button type="submit" name="action" value="sort">Sort A-Z</button>
</form>

<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

<h2>Original List (<?= count($fruits) ?> items)</h2>

<ul>
    <?php foreach ($fruits as $fruit): ?>
        <li><?= htmlspecialchars($fruit, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>

<h2>Fruits With 6 or More Letters</h2>

<ul>
    <?php foreach ($filteredFruits as $fruit): ?>
        <li><?= htmlspecialchars($fruit, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>

<h2>Uppercase Fruit List</h2>

<ul>
    <?php foreach ($upperFruits as $fruit): ?>
        <li><?= htmlspecialchars($fruit, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>


<h2>Product Catalog</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Product</th>
        <th>Price</th>
    </tr>

    <?php foreach ($catalog as $product): ?>
        <tr>
            <td><?= (int) $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>$<?= number_format($product['price'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>



</body>
</html>
