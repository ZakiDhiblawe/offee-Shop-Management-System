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
        $position = $_POST['position'];
        $schedule = $_POST['schedule'];

        $stmt = $connection->prepare('INSERT INTO Employees500 (naame500, position, schedule) VALUES (:name, :position, :schedule)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':schedule', $schedule);
        $stmt->execute();
    } elseif (isset($_POST['edit'])) {
        $employee_id = $_POST['employee_id'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $schedule = $_POST['schedule'];

        $stmt = $connection->prepare('UPDATE Employees500 SET naame500 = :name, position = :position, schedule = :schedule WHERE employee_id = :employee_id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':schedule', $schedule);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $employee_id = $_POST['employee_id'];

        $stmt = $connection->prepare('DELETE FROM Employees500 WHERE employee_id = :employee_id');
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();
    }
}

$employees = $connection->query('SELECT * FROM Employees500')->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h2>Employee Management</h2>
<form method="post">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Position:</label>
        <input type="text" name="position" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Schedule:</label>
        <input type="text" name="schedule" class="form-control" required>
    </div>
    <button type="submit" name="add" class="btn btn-primary">Add Employee</button>
</form>

<h3>Existing Employees</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Schedule</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?= htmlspecialchars($employee['NAAME500']) ?></td>
                <td><?= htmlspecialchars($employee['POSITION']) ?></td>
                <td><?= htmlspecialchars($employee['SCHEDULE']) ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="employee_id" value="<?= $employee['EMPLOYEE_ID'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <button class="btn btn-secondary btn-sm" onclick="populateEditForm(<?= $employee['EMPLOYEE_ID'] ?>, '<?= htmlspecialchars($employee['NAAME500']) ?>', '<?= htmlspecialchars($employee['POSITION']) ?>', '<?= htmlspecialchars($employee['SCHEDULE']) ?>')">Edit</button>
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
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="employee_id" id="editEmployeeId">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Position:</label>
                        <input type="text" name="position" id="editPosition" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Schedule:</label>
                        <input type="text" name="schedule" id="editSchedule" class="form-control" required>
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
function populateEditForm(id, name, position, schedule) {
    document.getElementById('editEmployeeId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editPosition').value = position;
    document.getElementById('editSchedule').value = schedule;
    $('#editModal').modal('show');
}
</script>

<?php include 'footer.php'; ?>
