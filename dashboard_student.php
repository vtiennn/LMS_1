<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];

// Lấy danh sách khóa học đã đăng ký
$stmt = $conn->prepare('SELECT c.id, c.title, c.description, e.enrolled_at FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.user_id = ?');
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách bài tập và điểm
$assignments = [];
if ($courses) {
    $course_ids = array_column($courses, 'id');
    $in = str_repeat('?,', count($course_ids) - 1) . '?';
    $sql = "SELECT a.*, s.id as submission_id, s.grade FROM assignments a LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ? WHERE a.course_id IN ($in)";
    $params = array_merge([$user_id], $course_ids);
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute($params);
    $assignments = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Toastr notification for enroll success -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('enroll_success') == '1') {
    toastr.success('Course enrollment successful!');
  }
});
</script>

<div class="container mt-6" style="margin-top:100px;">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
    <h4>Enrolled Courses</h4>
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Course</th>
                <th>Enrollment Time</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($courses): foreach ($courses as $course): ?>
            <tr>
                <td>
                    <strong><?php echo htmlspecialchars($course['title']); ?></strong><br>
                </td>
                <td class="text-muted">
                    <?php echo isset($course['enrolled_at']) ? date('d/m/Y H:i', strtotime($course['enrolled_at'])) : 'N/A'; ?>
                </td>
                <td class="text-end">
                    <a href="course_details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="3">You have not enrolled in any courses.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
