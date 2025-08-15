<?php
// all_enrollments.php - List all enrollments for admin
include '../includes/db.php';
include 'admin_header.php';

// Fetch all enrollments with user and course info
$sql = "SELECT e.id, u.name AS student_name, c.title AS course_title, e.enrolled_at FROM enrollments e
        JOIN users u ON e.user_id = u.id
        JOIN courses c ON e.course_id = c.id
        ORDER BY e.id DESC";
$enrollments = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">All Enrollments</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enrollment List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Enrolled At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($enrollments && count($enrollments) > 0): 
                            foreach($enrollments as $row):
                        ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['enrolled_at']); ?></td>
                                </tr>
                        <?php 
                            endforeach; 
                        else: ?>
                            <tr><td colspan="4" class="text-center">No enrollments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'admin_footer.php'; ?>
