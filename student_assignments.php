<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];
// Lấy danh sách các assignment của các khóa học mà student đã đăng ký
// Only show assignments that have NOT been submitted by the student
$stmt = $conn->prepare('SELECT a.*, c.title as course_title, u.name as instructor_name 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    JOIN assignments a ON a.course_id = c.id 
    JOIN users u ON c.created_by = u.id 
    LEFT JOIN submissions s ON s.assignment_id = a.id AND s.student_id = ? 
    WHERE e.user_id = ? AND s.id IS NULL 
    ORDER BY a.deadline DESC');
$stmt->execute([$user_id, $user_id]);
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-5" style="margin-top: 50px;">
    <h2>Assigned Assignments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Assignment</th>
                <th>Instructor</th>
                <th>Deadline</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($assignments): foreach ($assignments as $a): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['course_title']); ?></td>
                <td><?php echo htmlspecialchars($a['title']); ?></td>
                <td><?php echo htmlspecialchars($a['instructor_name']); ?></td>
                <td><?php echo htmlspecialchars($a['deadline']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($a['description'])); ?></td>
                <td>
                    <a href="submit_assignment.php?assignment_id=<?php echo urlencode($a['id']); ?>" class="btn btn-primary btn-sm">Submit</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6">No assignments assigned yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
