<?php
// instructor_materials.php - Quản lý tài liệu cho từng khóa học của giảng viên
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit();
}
require_once 'includes/db.php';
$instructor_id = $_SESSION['user_id'];

// Lấy danh sách khóa học của giảng viên
$stmt = $conn->prepare('SELECT id, title FROM courses WHERE created_by = ?');
$stmt->execute([$instructor_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý chọn khóa học
$selected_course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

// Thêm mới chapter
if (isset($_POST['add_chapter']) && $selected_course_id > 0 && !empty($_POST['chapter_title'])) {
    $chapter_title = trim($_POST['chapter_title']);
    $stmt = $conn->prepare('INSERT INTO chapters (course_id, title) VALUES (?, ?)');
    $stmt->execute([$selected_course_id, $chapter_title]);
    header('Location: instructor_materials.php?course_id=' . $selected_course_id);
    exit();
}
// Thêm mới lesson hoặc cập nhật file cho lesson
if (isset($_POST['add_lesson']) && $selected_course_id > 0 && !empty($_POST['lesson_title']) && isset($_POST['chapter_id']) && $_POST['chapter_id'] > 0) {
    $lesson_title = trim($_POST['lesson_title']);
    $chapter_id = intval($_POST['chapter_id']);
    $file_path = null;
    if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/materials/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        $file_name = basename($_FILES['lesson_file']['name']);
        $target_file = $upload_dir . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
        if (move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        }
    }
    // Nếu lesson_id có thì update file, không thì tạo mới lesson
    if (!empty($_POST['lesson_id'])) {
        $lesson_id = intval($_POST['lesson_id']);
        if ($file_path) {
            $stmt = $conn->prepare('UPDATE lessons SET file_path = ?, title = ? WHERE id = ? AND chapter_id = ?');
            $stmt->execute([$file_path, $lesson_title, $lesson_id, $chapter_id]);
        } else {
            $stmt = $conn->prepare('UPDATE lessons SET title = ? WHERE id = ? AND chapter_id = ?');
            $stmt->execute([$lesson_title, $lesson_id, $chapter_id]);
        }
    } else {
        $stmt = $conn->prepare('INSERT INTO lessons (chapter_id, title, file_path) VALUES (?, ?, ?)');
        $stmt->execute([$chapter_id, $lesson_title, $file_path]);
    }
    header('Location: instructor_materials.php?course_id=' . $selected_course_id);
    exit();
}
// Xóa lesson
if (isset($_POST['delete_lesson']) && isset($_POST['lesson_id'])) {
    $stmt = $conn->prepare('DELETE FROM lessons WHERE id = ?');
    $stmt->execute([$_POST['lesson_id']]);
    header('Location: instructor_materials.php?course_id=' . $selected_course_id);
    exit();
}
// Lấy danh sách chapter và lesson
$chapters = [];
if ($selected_course_id > 0) {
    $stmt = $conn->prepare('SELECT * FROM chapters WHERE course_id = ? ORDER BY `order` ASC, id ASC');
    $stmt->execute([$selected_course_id]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($chapters as $idx => $chapter) {
        $stmt2 = $conn->prepare('SELECT * FROM lessons WHERE chapter_id = ? ORDER BY `order` ASC, id ASC');
        $stmt2->execute([$chapter['id']]);
        $chapters[$idx]['lessons'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Thêm tài liệu (upload file)
if (isset($_POST['add_material']) && $selected_course_id > 0 && !empty($_POST['title']) && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $title = trim($_POST['title']);
    $upload_dir = 'uploads/materials/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_name = basename($_FILES['file']['name']);
    $target_file = $upload_dir . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare('INSERT INTO materials (course_id, title, file_path) VALUES (?, ?, ?)');
        $stmt->execute([$selected_course_id, $title, $target_file]);
    }
    header('Location: instructor_materials.php?course_id=' . $selected_course_id);
    exit();
}
// Xóa tài liệu
if (isset($_POST['delete_material']) && isset($_POST['material_id'])) {
    $stmt = $conn->prepare('DELETE FROM materials WHERE id = ? AND course_id = ?');
    $stmt->execute([$_POST['material_id'], $selected_course_id]);
    header('Location: instructor_materials.php?course_id=' . $selected_course_id);
    exit();
}
// Sửa tài liệu
if (isset($_POST['edit_material']) && isset($_POST['material_id']) && !empty($_POST['title'])) {
    $title = trim($_POST['title']);
    // Không cho sửa file khi edit, chỉ sửa title
    $stmt = $conn->prepare('UPDATE materials SET title = ? WHERE id = ? AND course_id = ?');
    $stmt->execute([$title, $_POST['material_id'], $selected_course_id]);
    header('Location: instructor_materials.php?course_id=' . $selected_course_id);
    exit();
}
// Lấy danh sách tài liệu của khóa học đã chọn
$materials = [];
if ($selected_course_id > 0) {
    $stmt = $conn->prepare('SELECT * FROM materials WHERE course_id = ? ORDER BY id DESC');
    $stmt->execute([$selected_course_id]);
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php include 'header.php'; ?>
<div class="container mt-5" style="padding: 50px;">
    <h2>Course Materials</h2>
    <form method="get" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="course_id" class="col-form-label">Select Course:</label>
            </div>
            <div class="col-auto">
                <select name="course_id" id="course_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Choose --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php if ($selected_course_id == $c['id']) echo 'selected'; ?>><?php echo htmlspecialchars($c['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
    <?php if ($selected_course_id > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Add New Chapter</div>
            <div class="card-body">
                <form method="post" class="row g-2 align-items-center">
                    <div class="col-md-10">
                        <input type="text" name="chapter_title" class="form-control" placeholder="Chapter Title" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_chapter" class="btn btn-success w-100">Add Chapter</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Add New Lesson</div>
            <div class="card-body">
                <form method="post" class="row g-2 align-items-center" enctype="multipart/form-data">
                    <div class="col-md-3">
                        <select name="chapter_id" class="form-select" required>
                            <option value="">-- Select Chapter --</option>
                            <?php foreach ($chapters as $ch): ?>
                                <option value="<?php echo $ch['id']; ?>"><?php echo htmlspecialchars($ch['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="lesson_title" class="form-control" placeholder="Lesson Title" required>
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="lesson_file" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_lesson" class="btn btn-info w-100">Add/Update Lesson</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">Chapters & Lessons</div>
            <div class="card-body">
                <?php if (empty($chapters)): ?>
                    <div class="alert alert-info mb-0">No chapters for this course yet.</div>
                <?php else: ?>
                    <?php foreach ($chapters as $ch): ?>
                        <div class="mb-3 p-2 border rounded">
                            <div class="fw-bold mb-2">Chapter: <?php echo htmlspecialchars($ch['title']); ?></div>
                            <?php if (empty($ch['lessons'])): ?>
                                <div class="text-muted">No lessons in this chapter.</div>
                            <?php else: ?>
                                <ul class="list-group">
                                <?php foreach ($ch['lessons'] as $ls): ?>
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <div>
                                            <b><?php echo htmlspecialchars($ls['title']); ?></b>
                                            <?php if (!empty($ls['file_path'])): ?>
                                                - <a href="<?php echo htmlspecialchars($ls['file_path']); ?>" download>Download</a>
                                            <?php endif; ?>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" class="d-flex gap-2 align-items-center mb-0">
                                            <input type="hidden" name="lesson_id" value="<?php echo $ls['id']; ?>">
                                            <input type="hidden" name="chapter_id" value="<?php echo $ch['id']; ?>">
                                            <input type="text" name="lesson_title" value="<?php echo htmlspecialchars($ls['title']); ?>" class="form-control form-control-sm" required>
                                            <input type="file" name="lesson_file" class="form-control form-control-sm">
                                            <button type="submit" name="add_lesson" class="btn btn-warning btn-sm">Update</button>
                                            <button type="submit" name="delete_lesson" class="btn btn-danger btn-sm" onclick="return confirm('Delete this lesson?');">Delete</button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
