<?php
// instructors.php - List all instructor accounts for admin
include '../includes/db.php';
include 'admin_header.php';

// Fetch all instructors from users table
// Handle delete user
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Prevent deleting self or admin (optional, can be customized)
    if ($delete_id > 0) {
        $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$delete_id]);
        echo '<script>location.href="all_users.php?deleted=1";</script>';
        exit;
    }
}

// Handle update user
if (isset($_POST['update_user']) && is_numeric($_POST['user_id'])) {
    $uid = intval($_POST['user_id']);
    $uname = trim($_POST['edit_name'] ?? '');
    $uemail = trim($_POST['edit_email'] ?? '');
    $urole = trim($_POST['edit_role'] ?? '');
    if ($uname && $uemail && $urole) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$uname, $uemail, $urole, $uid]);
        echo '<script>location.href="all_users.php?updated=1";</script>';
        exit;
    }
}

$users = $conn->query("SELECT id, name, email, role FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Users</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User List</h6>
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
                        if ($users && count($users) > 0): 
                            foreach($users as $row):
                                if ($edit_id === intval($row['id'])): ?>
                                    <tr>
                                        <form method="post">
                                            <td><?php echo $row['id']; ?><input type="hidden" name="user_id" value="<?php echo $row['id']; ?>"></td>
                                            <td><input type="text" name="edit_name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
                                            <td><input type="email" name="edit_email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required></td>
                                            <td>
                                                <select name="edit_role" class="form-control" required>
                                                    <option value="admin" <?php if($row['role']==='admin')echo 'selected';?>>Admin</option>
                                                    <option value="instructor" <?php if($row['role']==='instructor')echo 'selected';?>>Instructor</option>
                                                    <option value="student" <?php if($row['role']==='student')echo 'selected';?>>Student</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" name="update_user" class="btn btn-success btn-sm">Save</button>
                                                <a href="all_users.php" class="btn btn-secondary btn-sm">Cancel</a>
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
                                            <a href="all_users.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="all_users.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endif;
                            endforeach; 
                        else: ?>
                            <tr><td colspan="5" class="text-center">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'admin_footer.php'; ?>
