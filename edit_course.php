<?php
// edit_course.php - Edit course details for instructors
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($course_id <= 0) {
    echo '<div class="alert alert-danger mt-5">Invalid course ID.</div>';
    include 'footer.php';
    exit;
}
$stmt = $conn->prepare('SELECT * FROM courses WHERE id = ? AND created_by = ?');
$stmt->execute([$course_id, $_SESSION['user_id']]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) {
    echo '<div class="alert alert-danger mt-5">Course not found or you do not have permission to edit.</div>';
    include 'footer.php';
    exit;
}

$message = '';
if (isset($_POST['update_course'])) {
    $new_title = trim($_POST['edit_title'] ?? '');
    $new_description = trim($_POST['edit_description'] ?? '');
    $new_thumbnail = $course['thumbnail'];
    if (isset($_FILES['edit_thumbnail']) && $_FILES['edit_thumbnail']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['edit_thumbnail']['tmp_name'];
        $fileName = basename($_FILES['edit_thumbnail']['name']);
        $uniqueName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        $targetDir = __DIR__ . '/uploads/course_thumbnails/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetFile = $targetDir . $uniqueName;
        if (move_uploaded_file($fileTmp, $targetFile)) {
            $new_thumbnail = 'uploads/course_thumbnails/' . $uniqueName;
        }
    }
    if ($new_title && $new_description) {
        $stmt = $conn->prepare('UPDATE courses SET title = ?, description = ?, thumbnail = ? WHERE id = ?');
        $stmt->execute([$new_title, $new_description, $new_thumbnail, $course_id]);
        $message = 'Course updated successfully!';
        // Reload course info
        $stmt = $conn->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = 'Please enter all required fields.';
    }
}
?>
<div class="container py-5" style="max-width:600px;margin-top:50px;">
    <h2>Edit Course</h2>
    <?php if ($message): ?><div class="alert alert-info mb-3"><?php echo $message; ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="edit_title" class="form-label">Course Title</label>
            <input type="text" class="form-control" id="edit_title" name="edit_title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="edit_description" class="form-label">Description</label>
            <textarea class="form-control" id="edit_description" name="edit_description" rows="3" required><?php echo htmlspecialchars($course['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="edit_thumbnail" class="form-label">Thumbnail</label>
            <input type="file" class="form-control" id="edit_thumbnail" name="edit_thumbnail" accept="image/*">
            <?php if (!empty($course['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Current Thumbnail" style="max-width:120px; margin-top:10px;">
            <?php endif; ?>
        </div>
        <button type="submit" name="update_course" class="btn btn-success">Save Changes</button>
        <a href="course_details.php?id=<?php echo $course_id; ?>" class="btn btn-secondary ms-2">Back</a>
    </form>
</div>
<?php include 'footer.php'; ?>
