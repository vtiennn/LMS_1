<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header('Location: authentication-login.php');
    exit;
}
include 'includes/db.php';
// Handle grading BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'], $_POST['grade'])) {
    $submission_id = $_POST['submission_id'];
    $grade = floatval($_POST['grade']);
    if ($grade >= 0 && $grade <= 100) {
        $stmt = $conn->prepare('UPDATE submissions SET grade = ? WHERE id = ?');
        $stmt->execute([$grade, $submission_id]);
        header('Location: instructor_submissions.php');
        exit;
    } else {
        $grade_error = 'Điểm phải nằm trong khoảng từ 0 đến 100.';
    }
}
include 'header.php';

$instructor_id = $_SESSION['user_id'];
// Lấy danh sách bài tập do instructor tạo
$stmt = $conn->prepare('SELECT a.*, c.title as course_title FROM assignments a JOIN courses c ON a.course_id = c.id WHERE c.created_by = ?');
$stmt->execute([$instructor_id]);
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy submissions cho các assignment này
$assignment_ids = array_column($assignments, 'id');
$submissions = [];
if ($assignment_ids) {
    $in = str_repeat('?,', count($assignment_ids) - 1) . '?';
    $sql = "SELECT s.*, u.name as student_name, a.title as assignment_title, a.deadline, c.title as course_title FROM submissions s JOIN users u ON s.student_id = u.id JOIN assignments a ON s.assignment_id = a.id JOIN courses c ON a.course_id = c.id WHERE s.assignment_id IN ($in) ORDER BY s.id DESC";
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute($assignment_ids);
    $submissions = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="container py-5" style="margin-top: 50px;">
    <?php if (!empty($grade_error)): ?>
      <div class="alert alert-danger text-center"><?php echo $grade_error; ?></div>
    <?php endif; ?>
    <h2>Student Submissions</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Assignment</th>
                <th>Student</th>
                <th>Submitted File</th>
                <th>Deadline</th>
                <th>Grade</th>
                <th>Grade Submission</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($submissions): foreach ($submissions as $s): ?>
            <tr>
                <td><?php echo htmlspecialchars($s['course_title']); ?></td>
                <td><?php echo htmlspecialchars($s['assignment_title']); ?></td>
                <td><?php echo htmlspecialchars($s['student_name']); ?></td>
                <td><a href="<?php echo htmlspecialchars($s['file_path']); ?>" target="_blank">View File</a></td>
                <td><?php echo htmlspecialchars($s['deadline']); ?></td>
                <td><?php echo is_null($s['grade']) ? 'Not graded' : $s['grade']; ?></td>
                <td>
                  <form method="post" action="instructor_submissions.php" style="display: flex; align-items: center; gap: 5px;">
                    <input type="hidden" name="submission_id" value="<?php echo $s['id']; ?>">
                    <input type="number" name="grade" min="0" max="100" step="0.1" value="<?php echo htmlspecialchars($s['grade']); ?>" style="width:80px;">
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                  </form>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7">No submissions yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
// Xử lý chấm điểm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'], $_POST['grade'])) {
    $submission_id = $_POST['submission_id'];
    $grade = floatval($_POST['grade']);
    if ($grade >= 0 && $grade <= 100) {
        $stmt = $conn->prepare('UPDATE submissions SET grade = ? WHERE id = ?');
        $stmt->execute([$grade, $submission_id]);
        header('Location: instructor_submissions.php');
        exit;
    } else {
        echo '<div class="alert alert-danger text-center">Điểm phải nằm trong khoảng từ 0 đến 100.</div>';
    }
}
include 'footer.php';
?>
