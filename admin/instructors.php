<?php
// instructors.php - List all instructor accounts for admin
include '../includes/db.php';
include 'admin_header.php';

// Handle delete instructor
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    if ($delete_id > 0) {
        $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'instructor'")->execute([$delete_id]);
        echo '<script>location.href="instructors.php?deleted=1";</script>';
        exit;
    }
}

// Handle update instructor
if (isset($_POST['update_instructor']) && is_numeric($_POST['instructor_id'])) {
    $iid = intval($_POST['instructor_id']);
    $iname = trim($_POST['edit_name'] ?? '');
    $iemail = trim($_POST['edit_email'] ?? '');
    if ($iname && $iemail) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'instructor'");
        $stmt->execute([$iname, $iemail, $iid]);
        echo '<script>location.href="instructors.php?updated=1";</script>';
        exit;
    }
}

$sql = "SELECT id, name, email, role FROM users WHERE role = 'instructor' ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Instructors</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Instructor Accounts</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
                        if ($stmt && $stmt->rowCount() > 0): 
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                if ($edit_id === intval($row['id'])): ?>
                                    <tr>
                                        <form method="post">
                                            <td><?php echo $row['id']; ?><input type="hidden" name="instructor_id" value="<?php echo $row['id']; ?>"></td>
                                            <td><input type="text" name="edit_name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
                                            <td><input type="email" name="edit_email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required></td>
                                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                                            <td>
                                                <button type="submit" name="update_instructor" class="btn btn-success btn-sm">Save</button>
                                                <a href="instructors.php" class="btn btn-secondary btn-sm">Cancel</a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                        <td>
                                            <a href="instructors.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="instructors.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this instructor?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endif;
                            endwhile; 
                        else: ?>
                            <tr><td colspan="5" class="text-center">No instructors found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'admin_footer.php'; ?>
