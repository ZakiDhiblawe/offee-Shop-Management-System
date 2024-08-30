<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
require 'db.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Code for adding a customer
        $name = $_POST['name'];
        $contact_info = $_POST['contact_info'];
        $loyalty_points = $_POST['loyalty_points'];

        $stmt = $connection->prepare('INSERT INTO Customers500 (naame500, contact_info, loyalty_points) VALUES (:name, :contact_info, :loyalty_points)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':contact_info', $contact_info);
        $stmt->bindParam(':loyalty_points', $loyalty_points);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        // Code for deleting a customer
        $customer_id = $_POST['customer_id'];
        $stmt = $connection->prepare('DELETE FROM Customers500 WHERE customer_id = :customer_id');
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
    } elseif (isset($_POST['edit'])) {
        // Code for editing a customer
        $customer_id = $_POST['customer_id'];
        $name = $_POST['name'];
        $contact_info = $_POST['contact_info'];
        $loyalty_points = $_POST['loyalty_points'];

        $stmt = $connection->prepare('UPDATE Customers500 SET naame500 = :name, contact_info = :contact_info, loyalty_points = :loyalty_points WHERE customer_id = :customer_id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':contact_info', $contact_info);
        $stmt->bindParam(':loyalty_points', $loyalty_points);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
    }
}

// Fetch existing customers
$customers = $connection->query('SELECT * FROM Customers500')->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="container">
    <h2>Customer Management</h2>
    <form method="post" class="mb-4">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Contact Info:</label>
            <input type="text" name="contact_info" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Loyalty Points:</label>
            <input type="number" name="loyalty_points" class="form-control" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Customer</button>
    </form>

    <h3>Existing Customers</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Info</th>
                <th>Loyalty Points</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['NAAME500']) ?></td>
                    <td><?= htmlspecialchars($customer['CONTACT_INFO']) ?></td>
                    <td><?= htmlspecialchars($customer['LOYALTY_POINTS']) ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="customer_id" value="<?= $customer['CUSTOMER_ID'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <!-- Edit button with modal -->
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editModal<?= $customer['CUSTOMER_ID'] ?>">Edit</button>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $customer['CUSTOMER_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Customer</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post">
                                            <input type="hidden" name="customer_id" value="<?= $customer['CUSTOMER_ID'] ?>">
                                            <div class="form-group">
                                                <label>Name:</label>
                                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['NAAME500']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Info:</label>
                                                <input type="text" name="contact_info" class="form-control" value="<?= htmlspecialchars($customer['CONTACT_INFO']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Loyalty Points:</label>
                                                <input type="number" name="loyalty_points" class="form-control" value="<?= htmlspecialchars($customer['LOYALTY_POINTS']) ?>" required>
                                            </div>
                                            <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
