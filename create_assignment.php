<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'instructor')) {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

// Lấy danh sách khóa học do giảng viên tạo
$stmt = $conn->prepare('SELECT id, title FROM courses WHERE created_by = ?');
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $deadline = $_POST['deadline'] ?? '';
    $file_path = '';
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['assignment_file']['tmp_name'];
        $fileName = basename($_FILES['assignment_file']['name']);
        $uniqueName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        $targetDir = __DIR__ . '/uploads/assignment_files/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetFile = $targetDir . $uniqueName;
        if (move_uploaded_file($fileTmp, $targetFile)) {
            $file_path = 'uploads/assignment_files/' . $uniqueName;
        }
    }
    if ($course_id && $title && $deadline) {
        // Check if assignments table has file_path column
        $has_file_path = false;
        $result = $conn->query("SHOW COLUMNS FROM assignments LIKE 'file_path'");
        if ($result && $result->rowCount() > 0) {
            $has_file_path = true;
        }
        if ($has_file_path) {
            $stmt = $conn->prepare('INSERT INTO assignments (course_id, title, description, deadline, file_path) VALUES (?, ?, ?, ?, ?)');
            $params = [$course_id, $title, $description, $deadline, $file_path];
        } else {
            $stmt = $conn->prepare('INSERT INTO assignments (course_id, title, description, deadline) VALUES (?, ?, ?, ?)');
            $params = [$course_id, $title, $description, $deadline];
        }
        if ($stmt->execute($params)) {
            $message = 'Assignment created successfully!';
        } else {
            $message = 'Error creating assignment.';
        }
    } else {
        $message = 'Please fill in all required fields.';
    }
}
?>

<div class="container mt-5" style="padding: 50px; max-width:600px;">
    <h2>Create New Assignment</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="course_id" class="form-label">Select Course</label>
            <select class="form-select" id="course_id" name="course_id" required>
                <option value="">-- Select --</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Assignment Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="assignment_file" class="form-label">Upload File</label>
            <input type="file" class="form-control" id="assignment_file" name="assignment_file" accept="*/*">
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="datetime-local" class="form-control" id="deadline" name="deadline" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Assignment</button>
    </form>
</div>

<?php include 'footer.php'; ?>
