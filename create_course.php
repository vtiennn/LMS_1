


<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'instructor')) {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $created_by = $_SESSION['user_id'];
    $chapters = $_POST['chapters'] ?? [];
    // Xử lý upload thumbnail
    $thumbnail_path = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['thumbnail']['tmp_name'];
        $fileName = basename($_FILES['thumbnail']['name']);
        $uniqueName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        $targetDir = __DIR__ . '/uploads/course_thumbnails/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetFile = $targetDir . $uniqueName;
        if (move_uploaded_file($fileTmp, $targetFile)) {
            $thumbnail_path = 'uploads/course_thumbnails/' . $uniqueName;
        } else {
            error_log('Upload thumbnail failed: ' . $fileTmp . ' to ' . $targetFile);
            $thumbnail_path = null;
        }
    }
    if ($title) {
        $conn->beginTransaction();
        try {
            $stmt = $conn->prepare('INSERT INTO courses (title, description, created_by, thumbnail) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $description, $created_by, $thumbnail_path]);
            $course_id = $conn->lastInsertId();
            foreach ($chapters as $cIdx => $chapter) {
                $chapter_title = trim($chapter['title'] ?? '');
                if ($chapter_title === '') continue;
                $stmt = $conn->prepare('INSERT INTO chapters (course_id, title) VALUES (?, ?)');
                $stmt->execute([$course_id, $chapter_title]);
                $chapter_id = $conn->lastInsertId();
                if (!empty($chapter['lessons']) && is_array($chapter['lessons'])) {
                    foreach ($chapter['lessons'] as $lIdx => $lesson) {
                        $lesson_title = trim($lesson);
                        if ($lesson_title === '') continue;
                        // Xử lý file upload
                        $file_path = null;
                        if (isset($_FILES['chapters']['name'][$cIdx]['lesson_files'][$lIdx]) && $_FILES['chapters']['error'][$cIdx]['lesson_files'][$lIdx] === UPLOAD_ERR_OK) {
                            $fileTmp = $_FILES['chapters']['tmp_name'][$cIdx]['lesson_files'][$lIdx];
                            $fileName = basename($_FILES['chapters']['name'][$cIdx]['lesson_files'][$lIdx]);
                            $uniqueName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
                            $targetDir = __DIR__ . '/uploads/lesson_files/';
                            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                            $targetFile = $targetDir . $uniqueName;
                            if (move_uploaded_file($fileTmp, $targetFile)) {
                                // Đường dẫn public cho web (tương đối từ gốc web)
                                $file_path = 'uploads/lesson_files/' . $uniqueName;
                            } else {
                                error_log('Upload failed: ' . $fileTmp . ' to ' . $targetFile);
                                $file_path = null;
                            }
                        }
                        $stmt = $conn->prepare('INSERT INTO lessons (chapter_id, title, file_path) VALUES (?, ?, ?)');
                        $stmt->execute([$chapter_id, $lesson_title, $file_path]);
                    }
                }
            }
            $conn->commit();
            $message = 'Create a successful course!';
        } catch (Exception $e) {
            $conn->rollBack();
            $message = 'An error occurred while creating the course: ' . $e->getMessage();
        }
    } else {
        $message = 'Please enter course name.';
    }
}
?>
<div class="container" style="max-width:800px; padding-top:120px;">
    <h2>Create new course</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
<form method="post" id="create-course-form" autocomplete="off" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Course Name</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Course Thumbnail</label>
            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
        </div>
        <hr>
        <h5>Chapters & Lessons</h5>
        <div id="chapters-list"></div>
        <button type="button" class="btn btn-success my-2" onclick="addChapter()">Add Chapter</button>
        <hr>
        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>
</div>
<script>
let chapterIdx = 0;
function addChapter() {
    const chaptersList = document.getElementById('chapters-list');
    const idx = chapterIdx++;
    const chapterDiv = document.createElement('div');
    chapterDiv.className = 'card mb-3';
    chapterDiv.innerHTML = `
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <input type="text" name="chapters[${idx}][title]" class="form-control bg-light text-dark me-2 chapter-title-input" placeholder="Chapter Name" required style="max-width:300px;">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.card').remove()">Delete Chapter</button>
        </div>
        <div class="card-body">
            <div class="lessons-list"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-lesson-btn" style="display:none;" onclick="addLesson(this, ${idx})">Add Lesson</button>
        </div>
    `;
    chaptersList.appendChild(chapterDiv);

    // Lấy input và nút thêm bài học
    const input = chapterDiv.querySelector('.chapter-title-input');
    const addLessonBtn = chapterDiv.querySelector('.add-lesson-btn');
    // Hiển thị nút khi input có giá trị
    input.addEventListener('input', function() {
        if (input.value.trim() !== '') {
            addLessonBtn.style.display = '';
        } else {
            addLessonBtn.style.display = 'none';
        }
    });
}
function addLesson(btn, chapterIdx) {
    const lessonsList = btn.parentElement.querySelector('.lessons-list');
    const lessonDiv = document.createElement('div');
    lessonDiv.className = 'input-group mb-2 align-items-center';
    lessonDiv.innerHTML = `
        <input type="text" name="chapters[${chapterIdx}][lessons][]" class="form-control" placeholder="Lesson Name" required style="max-width: 200px;">
        <input type="file" name="chapters[${chapterIdx}][lesson_files][]" class="form-control ms-2" style="max-width: 220px;">
        <button type="button" class="btn btn-outline-danger ms-2" onclick="this.parentElement.remove()">Delete</button>
    `;
    lessonsList.appendChild(lessonDiv);
}
</script>
<?php include 'footer.php'; ?>
