<?php
// pages/topbar-crud.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../components/db.php';

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $status = $_POST['status'] === 'inactive' ? 'inactive' : 'active';
    $id = $_POST['id'] ?? '';
    if ($id) {
        // Update
        $stmt = $pdo->prepare('UPDATE topbar SET email=?, location=?, address=?, status=? WHERE id=?');
        $stmt->execute([$email, $location, $address, $status, $id]);
    } else {
        // Create
        $stmt = $pdo->prepare('INSERT INTO topbar (email, location, address, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$email, $location, $address, $status]);
    }
    header('Location: topbar-crud.php');
    exit;
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM topbar WHERE id=?');
    $stmt->execute([$id]);
    header('Location: topbar-crud.php');
    exit;
}
// Fetch all topbar records
$topbars = $pdo->query('SELECT * FROM topbar')->fetchAll();
// Fetch for edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM topbar WHERE id=?');
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topbar Management</title>
    <link rel="stylesheet" href="../styles/logo.css">
    <style>
        body { font-family: 'Quicksand', Arial, sans-serif; background: #f4f7fa; margin: 0; }
        .layout { display: flex; min-height: 100vh; }
        .crud-container { flex: 1; max-width: 700px; margin: 2rem auto; background: #fff; border-radius: 0.5rem; box-shadow: 0 4px 16px #08204b22; padding: 2rem; }
        h2 { color: #08204b; }
        .add-btn { background: #08204b; color: #fff; border: none; border-radius: 0.25rem; padding: 0.7rem 1.5rem; font-size: 1rem; font-weight: 700; cursor: pointer; margin-bottom: 1rem; }
        .add-btn:hover { background: #0a2a6c; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.7rem 0.5rem; border-bottom: 1px solid #e0e0e0; text-align: left; }
        th { background: #08204b; color: #fff; }
        tr:last-child td { border-bottom: none; }
        .actions a { margin-right: 0.7rem; color: #08204b; text-decoration: underline; }
        .status-active { color: #27ae60; font-weight: 700; }
        .status-inactive { color: #c0392b; font-weight: 700; }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(8,32,75,0.25); }
        .modal-content { background: #fff; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; max-width: 400px; box-shadow: 0 4px 16px #08204b22; position: relative; }
        .close { position: absolute; top: 1rem; right: 1rem; font-size: 1.5rem; color: #08204b; cursor: pointer; }
        form { display: flex; flex-direction: column; gap: 1rem; }
        label { font-weight: 600; }
        input, select { padding: 0.5rem 1rem; border: 1px solid #cfd8dc; border-radius: 0.25rem; font-size: 1rem; }
        button[type="submit"] { background: #08204b; color: #fff; border: none; border-radius: 0.25rem; padding: 0.7rem 1.5rem; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 0.5rem; }
        button[type="submit"]:hover { background: #0a2a6c; }
    </style>
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . '/../components/SidebarComponent.php'; ?>
        <div class="crud-container">
            <h2>Topbar Records</h2>
            <button class="add-btn" onclick="openModal()">Add Topbar Info</button>
            <table>
                <tr>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($topbars as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td class="status-<?php echo htmlspecialchars($row['status']); ?>"><?php echo ucfirst($row['status']); ?></td>
                    <td class="actions">
                        <a href="?edit=<?php echo $row['id']; ?>">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <!-- Modal for Add/Edit -->
            <div id="topbarModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2 id="modalTitle">Add Topbar Info</h2>
                    <form id="topbarForm" method="post">
                        <input type="hidden" name="id" id="modalId" value="">
                        <label>Email</label>
                        <input type="email" name="email" id="modalEmail" required value="">
                        <label>Location</label>
                        <input type="text" name="location" id="modalLocation" required value="">
                        <label>Address</label>
                        <input type="text" name="address" id="modalAddress" required value="">
                        <label>Status</label>
                        <select name="status" id="modalStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <button type="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openModal(edit = null) {
            document.getElementById('topbarModal').style.display = 'block';
            document.getElementById('modalTitle').textContent = edit ? 'Edit Topbar Info' : 'Add Topbar Info';
            document.getElementById('topbarForm').querySelector('button[type="submit"]').textContent = edit ? 'Update' : 'Add';
            if (edit) {
                document.getElementById('modalId').value = edit.id;
                document.getElementById('modalEmail').value = edit.email;
                document.getElementById('modalLocation').value = edit.location;
                document.getElementById('modalAddress').value = edit.address;
                document.getElementById('modalStatus').value = edit.status;
            } else {
                document.getElementById('modalId').value = '';
                document.getElementById('modalEmail').value = '';
                document.getElementById('modalLocation').value = '';
                document.getElementById('modalAddress').value = '';
                document.getElementById('modalStatus').value = 'active';
            }
        }
        function closeModal() {
            document.getElementById('topbarModal').style.display = 'none';
        }
        // Open modal for edit
        <?php if ($edit): ?>
        openModal(<?php echo json_encode($edit); ?>);
        <?php endif; ?>
        // Close modal on outside click
        window.onclick = function(event) {
            var modal = document.getElementById('topbarModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
