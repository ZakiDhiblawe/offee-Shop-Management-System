<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
include 'header.php';
?>

<h2>Dashboard</h2>
<ul class="list-group">
    <li class="list-group-item"><a href="customers.php">Customer Management</a></li>
    <li class="list-group-item"><a href="employees.php">Employee Management</a></li>
    <li class="list-group-item"><a href="products.php">Product Management</a></li>
    <li class="list-group-item"><a href="orders.php">Order Management</a></li>
    <li class="list-group-item"><a href="sales_report.php">Sales Reporting</a></li>
</ul>

<?php include 'footer.php'; ?>
