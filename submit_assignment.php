<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

// Lấy danh sách bài tập của các khóa học đã đăng ký
$stmt = $conn->prepare('SELECT a.id, a.title, a.deadline, c.title as course_title FROM enrollments e JOIN assignments a ON e.course_id = a.course_id JOIN courses c ON c.id = a.course_id WHERE e.user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assignment_id'])) {
    $assignment_id = $_POST['assignment_id'];
    $student_id = $_SESSION['user_id'];
    $file = $_FILES['file'] ?? null;
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'uploads/submissions/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = uniqid() . '_' . basename($file['name']);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare('INSERT INTO submissions (assignment_id, student_id, file_path) VALUES (?, ?, ?)');
            if ($stmt->execute([$assignment_id, $student_id, $target_file])) {
                $message = 'Submission successful!';
            } else {
                $message = 'Error saving data.';
            }
        } else {
            $message = 'File upload error.';
        }
    } else {
        $message = 'Please select a file.';
    }
}
?>

<div class="container mt-5" style="padding:50px; max-width:700px;">
    <h2>Submit Assignment</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="assignment_id" class="form-label">Select Assignment</label>
            <select class="form-select" id="assignment_id" name="assignment_id" required>
                <option value="">-- Select --</option>
                <?php foreach ($assignments as $a): ?>
                    <option value="<?php echo $a['id']; ?>">
                        <?php echo htmlspecialchars($a['course_title'] . ' - ' . $a['title'] . ' (Deadline: ' . $a['deadline'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Select File</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include 'footer.php'; ?>
