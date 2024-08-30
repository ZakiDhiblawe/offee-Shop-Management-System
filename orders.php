<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product price
    $stmt = $connection->prepare('SELECT price FROM Products500 WHERE product_id = :product_id');
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_amount = $product['price'] * $quantity;

    $connection->beginTransaction();

    try {
        // Insert into Orders500 table and get the last inserted order ID
        $stmt = $connection->prepare('INSERT INTO Orders500 (customer_id, total_amount) VALUES (:customer_id, :total_amount) RETURNING order_id INTO :order_id');
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':total_amount', $total_amount);

        // Create a variable to hold the returned order ID and bind it
        $order_id = 0;
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        // Insert into OrderDetails500 table
        $stmt = $connection->prepare('INSERT INTO OrderDetails500 (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $total_amount);
        $stmt->execute();

        $connection->commit();
    } catch (Exception $e) {
        $connection->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}

// Fetch customers for dropdown
$customers = $connection->query('SELECT * FROM Customers500')->fetchAll(PDO::FETCH_ASSOC);
// Fetch products for dropdown
$products = $connection->query('SELECT * FROM Products500')->fetchAll(PDO::FETCH_ASSOC);
// Fetch existing orders
$orders = $connection->query('
    SELECT 
        o.order_id, 
        c.naame500 AS customer_name, 
        p.naame500 AS product_name, 
        od.quantity, 
        o.total_amount 
    FROM Orders500 o
    JOIN OrderDetails500 od ON o.order_id = od.order_id
    JOIN Customers500 c ON o.customer_id = c.customer_id
    JOIN Products500 p ON od.product_id = p.product_id
')->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h2>Order Management</h2>
<form method="post">
    <div class="form-group">
        <label>Customer:</label>
        <select name="customer_id" class="form-control" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['CUSTOMER_ID'] ?>"><?= htmlspecialchars($customer['NAAME500']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Product:</label>
        <select name="product_id" class="form-control" required>
            <?php foreach ($products as $product): ?>
                <option value="<?= $product['PRODUCT_ID'] ?>"><?= htmlspecialchars($product['NAAME500']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Quantity:</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Create Order</button>
</form>

<h3>Existing Orders</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['ORDER_ID']) ?></td>
                <td><?= htmlspecialchars($order['CUSTOMER_NAME']) ?></td>
                <td><?= htmlspecialchars($order['PRODUCT_NAME']) ?></td>
                <td><?= htmlspecialchars($order['QUANTITY']) ?></td>
                <td>$<?= htmlspecialchars($order['TOTAL_AMOUNT']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
