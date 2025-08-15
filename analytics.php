<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];

// Tổng số khóa học đã đăng ký
$stmt = $conn->prepare('SELECT COUNT(*) FROM enrollments WHERE user_id = ?');
$stmt->execute([$user_id]);
$total_courses = $stmt->fetchColumn();

// Tổng số bài tập đã giao
$stmt = $conn->prepare('SELECT COUNT(a.id) FROM enrollments e JOIN assignments a ON e.course_id = a.course_id WHERE e.user_id = ?');
$stmt->execute([$user_id]);
$total_assignments = $stmt->fetchColumn();

// Số bài đã nộp
$stmt = $conn->prepare('SELECT COUNT(DISTINCT s.assignment_id) FROM submissions s WHERE s.student_id = ?');
$stmt->execute([$user_id]);
$total_submitted = $stmt->fetchColumn();

// Điểm trung bình
$stmt = $conn->prepare('SELECT AVG(grade) FROM submissions WHERE student_id = ? AND grade IS NOT NULL');
$stmt->execute([$user_id]);
$avg_grade = $stmt->fetchColumn();

?>
<div class="container mt-5" style="max-width:700px;">
    <h2>Learning Analytics</h2>
    <ul class="list-group mb-4">
        <li class="list-group-item">Total enrolled courses: <b><?php echo $total_courses; ?></b></li>
        <li class="list-group-item">Total assignments: <b><?php echo $total_assignments; ?></b></li>
        <li class="list-group-item">Assignments submitted: <b><?php echo $total_submitted; ?></b></li>
        <li class="list-group-item">Average grade: <b><?php echo is_null($avg_grade) ? 'N/A' : round($avg_grade,2); ?></b></li>
    </ul>
    <!-- You can add charts, progress bars, ... -->
</div>
<?php include 'footer.php'; ?>
