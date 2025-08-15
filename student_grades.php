<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$assignments = [];
// Lấy danh sách khóa học đã đăng ký
$stmt = $conn->prepare('SELECT c.id FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.user_id = ?');
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<div class="container mt-5" style="padding: 30px;">
    <h2>Grades</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Assignment</th>
                <th>Deadline</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($assignments): foreach ($assignments as $a): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['course_id']); ?></td>
                <td><?php echo htmlspecialchars($a['title']); ?></td>
                <td><?php echo htmlspecialchars($a['deadline']); ?></td>
                <td><?php echo is_null($a['grade']) ? 'N/A' : $a['grade']; ?></td>
                <td>
                  <?php
                    if (!empty($a['submission_id'])) {
                        echo '<span class="text-success">Submitted</span> ';
                        echo '<a href="submit_assignment.php?assignment_id=' . urlencode($a['id']) . '" class="btn btn-warning btn-sm ms-2">Edit Submission</a>';
                    } else {
                        $now = new DateTime();
                        $deadline = new DateTime($a['deadline']);
                        if ($now < $deadline) {
                            $interval = $now->diff($deadline);
                            $parts = [];
                            if ($interval->d > 0) $parts[] = $interval->d . ' days';
                            if ($interval->h > 0) $parts[] = $interval->h . ' hours';
                            if ($interval->i > 0) $parts[] = $interval->i . ' minutes';
                            echo 'Remaining ' . implode(' ', $parts);
                            echo ' <a href="submit_assignment.php?assignment_id=' . urlencode($a['id']) . '" class="btn btn-primary btn-sm ms-2">Submit</a>';
                        } else {
                            echo '<span class="text-danger">Overdue</span>';
                        }
                    }
                  ?>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="5">No assignments yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
