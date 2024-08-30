<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];

        $stmt = $connection->prepare('INSERT INTO Products500 (naame500, price, stock_quantity) VALUES (:name, :price, :stock_quantity)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->execute();
    } elseif (isset($_POST['edit'])) {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];

        $stmt = $connection->prepare('UPDATE Products500 SET naame500 = :name, price = :price, stock_quantity = :stock_quantity WHERE product_id = :product_id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $product_id = $_POST['product_id'];

        $stmt = $connection->prepare('DELETE FROM Products500 WHERE product_id = :product_id');
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }
}

$products = $connection->query('SELECT * FROM Products500')->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h2>Product Management</h2>
<form method="post">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Price:</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Stock Quantity:</label>
        <input type="number" name="stock_quantity" class="form-control" required>
    </div>
    <button type="submit" name="add" class="btn btn-primary">Add Product</button>
</form>

<h3>Existing Products</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['NAAME500']) ?></td>
                <td>$<?= htmlspecialchars($product['PRICE']) ?></td>
                <td><?= htmlspecialchars($product['STOCK_QUANTITY']) ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="product_id" value="<?= $product['PRODUCT_ID'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <button class="btn btn-secondary btn-sm" onclick="populateEditForm(<?= $product['PRODUCT_ID'] ?>, '<?= htmlspecialchars($product['NAAME500']) ?>', <?= $product['PRICE'] ?>, <?= $product['STOCK_QUANTITY'] ?>)">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Edit Form Modal -->
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="editProductId">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Price:</label>
                        <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity:</label>
                        <input type="number" name="stock_quantity" id="editStockQuantity" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function populateEditForm(id, name, price, stock_quantity) {
    document.getElementById('editProductId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editPrice').value = price;
    document.getElementById('editStockQuantity').value = stock_quantity;
    $('#editModal').modal('show');
}
</script>

<?php include 'footer.php'; ?>
