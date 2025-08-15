<?php
// instructor_analytics.php - Analytics for instructor's own courses
include 'header.php';
include 'includes/db.php';
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit;
}
$instructor_id = $_SESSION['user_id'];
// Tổng số khóa học của giảng viên
$stmt = $conn->prepare('SELECT COUNT(*) FROM courses WHERE created_by = ?');
$stmt->execute([$instructor_id]);
$total_courses = $stmt->fetchColumn();
// Tổng số học viên đăng ký các khóa học của giảng viên
$stmt = $conn->prepare('SELECT COUNT(e.id) FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.created_by = ?');
$stmt->execute([$instructor_id]);
$total_enrollments = $stmt->fetchColumn();
// Tổng số bài tập đã giao
$stmt = $conn->prepare('SELECT COUNT(a.id) FROM assignments a JOIN courses c ON a.course_id = c.id WHERE c.created_by = ?');
$stmt->execute([$instructor_id]);
$total_assignments = $stmt->fetchColumn();
// Tổng số bài nộp
$stmt = $conn->prepare('SELECT COUNT(s.id) FROM submissions s JOIN assignments a ON s.assignment_id = a.id JOIN courses c ON a.course_id = c.id WHERE c.created_by = ?');
$stmt->execute([$instructor_id]);
$total_submissions = $stmt->fetchColumn();
// Điểm trung bình các bài đã chấm
$stmt = $conn->prepare('SELECT AVG(s.grade) FROM submissions s JOIN assignments a ON s.assignment_id = a.id JOIN courses c ON a.course_id = c.id WHERE c.created_by = ? AND s.grade IS NOT NULL');
$stmt->execute([$instructor_id]);
$avg_grade = $stmt->fetchColumn();
?>
<div class="container mt-5" style="max-width:700px;">
    <h2>Your Course Analytics</h2>
    <ul class="list-group mb-4">
        <li class="list-group-item">Total Courses: <b><?php echo $total_courses; ?></b></li>
        <li class="list-group-item">Total Enrollments: <b><?php echo $total_enrollments; ?></b></li>
        <li class="list-group-item">Total Assignments Given: <b><?php echo $total_assignments; ?></b></li>
        <li class="list-group-item">Total Submissions: <b><?php echo $total_submissions; ?></b></li>
        <li class="list-group-item">Average Graded Score: <b><?php echo is_null($avg_grade) ? 'N/A' : round($avg_grade,2); ?></b></li>
    </ul>
    <!-- You can add charts, progress bars, etc. -->
</div>
