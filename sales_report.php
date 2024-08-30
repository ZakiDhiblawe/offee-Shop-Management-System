<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
require 'db.php';

$sales_report = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $connection->prepare("
        SELECT 
            o.order_id, 
            c.naame500 AS customer_name, 
            p.naame500 AS product_name, 
            o.order_date, 
            od.price AS total_amount
        FROM Orders500 o
        JOIN OrderDetails500 od ON o.order_id = od.order_id
        JOIN Customers500 c ON o.customer_id = c.customer_id
        JOIN Products500 p ON od.product_id = p.product_id
        WHERE o.order_date BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
    ");
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->execute();
    $sales_report = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'header.php';
?>

<h2>Sales Reporting</h2>
<form method="post">
    <div class="form-group">
        <label>Start Date:</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label>End Date:</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Generate Report</button>
</form>

<?php if ($sales_report): ?>
    <h3>Sales Report</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Order Date</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales_report as $report): ?>
                <tr>
                    <td><?= htmlspecialchars($report['ORDER_ID']) ?></td>
                    <td><?= htmlspecialchars($report['CUSTOMER_NAME']) ?></td>
                    <td><?= htmlspecialchars($report['PRODUCT_NAME']) ?></td>
                    <td><?= htmlspecialchars($report['ORDER_DATE']) ?></td>
                    <td>$<?= htmlspecialchars($report['TOTAL_AMOUNT']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'footer.php'; ?>
