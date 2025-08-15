<?php
// all_courses.php - List all courses for admin
include '../includes/db.php';
include 'admin_header.php';

// Handle delete course
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    if ($delete_id > 0) {
        $conn->prepare("DELETE FROM courses WHERE id = ?")->execute([$delete_id]);
        echo '<script>location.href="all_courses.php?deleted=1";</script>';
        exit;
    }
}

// Handle update course
if (isset($_POST['update_course']) && is_numeric($_POST['course_id'])) {
    $cid = intval($_POST['course_id']);
    $ctitle = trim($_POST['edit_title'] ?? '');
    $cdesc = trim($_POST['edit_description'] ?? '');
    if ($ctitle && $cdesc) {
        $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$ctitle, $cdesc, $cid]);
        echo '<script>location.href="all_courses.php?updated=1";</script>';
        exit;
    }
}

$courses = $conn->query("SELECT id, title, description, created_by FROM courses ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">All Courses</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Course List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Created By ID Instructor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
                        if ($courses && count($courses) > 0): 
                            foreach($courses as $row):
                                if ($edit_id === intval($row['id'])): ?>
                                    <tr>
                                        <form method="post">
                                            <td><?php echo $row['id']; ?><input type="hidden" name="course_id" value="<?php echo $row['id']; ?>"></td>
                                            <td><input type="text" name="edit_title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required></td>
                                            <td><textarea name="edit_description" class="form-control" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea></td>
                                            <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                            <td>
                                                <button type="submit" name="update_course" class="btn btn-success btn-sm">Save</button>
                                                <a href="all_courses.php" class="btn btn-secondary btn-sm">Cancel</a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                        <td>
                                            <a href="all_courses.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="all_courses.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endif;
                            endforeach; 
                        else: ?>
                            <tr><td colspan="5" class="text-center">No courses found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'admin_footer.php'; ?>
