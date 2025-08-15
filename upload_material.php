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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $title = trim($_POST['title'] ?? '');
    $file = $_FILES['file'] ?? null;
    if ($title && $file && $file['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'uploads/materials/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = uniqid() . '_' . basename($file['name']);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare('INSERT INTO materials (course_id, title, file_path) VALUES (?, ?, ?)');
            if ($stmt->execute([$course_id, $title, $target_file])) {
                $message = 'Material uploaded successfully!';
            } else {
                $message = 'Error saving data.';
            }
        } else {
            $message = 'File upload error.';
        }
    } else {
        $message = 'Please enter a title and select a file.';
    }
}
?>

<div class="container mt-5" style="max-width:600px;">
    <h2>Upload Material to Course</h2>
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
            <label for="title" class="form-label">Material Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Select File</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<?php include 'footer.php'; ?>
