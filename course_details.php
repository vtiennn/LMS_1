<?php
include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Lấy id khóa học từ URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$course = null;
if ($course_id > 0) {
    $stmt = $conn->prepare('SELECT c.*, u.name as instructor_name FROM courses c LEFT JOIN users u ON c.created_by = u.id WHERE c.id = ?');
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Xử lý đăng ký học
if (isset($_POST['enroll']) && isset($course['id']) && isset($_SESSION['user_id'])) {
    // Kiểm tra đã đăng ký chưa
    $stmt = $conn->prepare('SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?');
    $stmt->execute([$_SESSION['user_id'], $course['id']]);
    if (!$stmt->fetch()) {
        $stmt = $conn->prepare('INSERT INTO enrollments (user_id, course_id, enrolled_at) VALUES (?, ?, NOW())');
        $stmt->execute([$_SESSION['user_id'], $course['id']]);
    }
    echo '<script>location.href="course_details.php?id=' . $course['id'] . '&enroll_success=1";</script>';
    exit;
}

// Kiểm tra đã đăng ký khóa học này chưa
$is_enrolled = false;
if (isset($_SESSION['user_id']) && isset($course['id'])) {
    $stmt = $conn->prepare('SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?');
    $stmt->execute([$_SESSION['user_id'], $course['id']]);
    if ($stmt->fetch()) {
        $is_enrolled = true;
    }
}


// Lấy danh sách chương và bài học thật từ DB
$chapters = [];
$total_chapters = 0;
$total_lessons = 0;
if ($course_id > 0) {
    $stmt = $conn->prepare('SELECT * FROM chapters WHERE course_id = ? ORDER BY `order` ASC, id ASC');
    $stmt->execute([$course_id]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_chapters = count($chapters);
    foreach ($chapters as $idx => $chapter) {
        $stmt2 = $conn->prepare('SELECT * FROM lessons WHERE chapter_id = ? ORDER BY `order` ASC, id ASC');
        $stmt2->execute([$chapter['id']]);
        $lessons = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $chapters[$idx]['lessons'] = $lessons;
        $total_lessons += count($lessons);
    }
}

?>

<div class="container py-5">
  <?php if ($course): ?>
  <div class="row g-4" style="padding: 50px">
    <div class="col-lg-8">
      <div class="mb-3">
        <div class="course-detail-title"><?php echo htmlspecialchars($course['title']); ?></div>
        <div class="course-detail-desc mt-2"><?php echo nl2br(htmlspecialchars($course['description'])); ?></div>
      </div>
      <div class="d-flex align-items-center gap-3 mb-4" style="font-size:1.1rem;">
        <span><b><?php echo $total_chapters; ?></b> chapter</span>
        <span>• <b><?php echo $total_lessons; ?></b> lesson</span>
      </div>
      <div class="course-outline-title">Course content <button class="expand-btn" onclick="expandAllChapters()">Expand all</button></div>
      <div id="course-outline">
        <?php foreach ($chapters as $i => $chapter): ?>
          <div class="chapter-box p-3 mb-2">
            <div class="chapter-title mb-2">
              <button class="expand-btn" onclick="toggleChapter(<?php echo $i; ?>)">
                <span id="icon-chapter-<?php echo $i; ?>">+</span>
              </button>
              <?php echo ($i+1) . '. ' . htmlspecialchars($chapter['title']); ?>
              <span class="text-muted" style="font-size:0.95rem; float:right;">
                <?php echo isset($chapter['lessons']) ? count($chapter['lessons']) : 0; ?> lesson
              </span>
            </div>
            <div id="chapter-lessons-<?php echo $i; ?>" style="display:<?php echo $i==0?'block':'none'; ?>;">
              <?php if (isset($chapter['lessons']) && count($chapter['lessons'])): ?>
                <?php foreach ($chapter['lessons'] as $idx => $lesson): ?>
                  <div class="lesson-row d-flex align-items-center mb-2">
                    <span class="me-2" style="color:#e74c3c;">●</span>
                    <span class="lesson-title flex-grow-1"><?php echo ($idx+1) . '. ' . htmlspecialchars($lesson['title']); ?></span>
                    <?php if (!empty($lesson['file_path'])): ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo htmlspecialchars($lesson['file_path']); ?>" class="btn btn-sm btn-outline-primary ms-2" download>Download file</a>
    <?php else: ?>
        <button class="btn btn-sm btn-outline-secondary ms-2" disabled title="Please login to download file">Download file</button>
    <?php endif; ?>
<?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="lesson-row text-muted">...</div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="course-sidebar">
        <div class="course-video mb-3">
          <?php if (!empty($course['thumbnail'])): ?>
            <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" class="w-100" alt="Course Thumbnail">
          <?php else: ?>
            <img src="assets/images/new-arrival/01.webp" class="w-100" alt="Course Image">
          <?php endif; ?>
        </div>
        <?php if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'instructor'): ?>
          <?php if ($is_enrolled): ?>
            <div class="alert alert-success text-center">You have registered for this course.</div>
          <?php else: ?>
            <form method="post" style="margin-bottom:1rem;">
              <button type="submit" name="enroll" class="btn btn-primary w-100">Register to study</button>
            </form>
          <?php endif; ?>
        <?php endif; ?>
        <ul class="info-list">
          <li><i class="bi bi-bar-chart"></i> Basic level</li>
          <li><i class="bi bi-collection-play"></i> Total <b><?php echo $total_lessons; ?></b> lesson</li>
          <li><i class="bi bi-laptop"></i> Learn anytime, anywhere</li>
          <li><i class="bi bi-person-badge"></i> Instructor: <b><?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown'); ?></b></li>
        </ul>
        <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'instructor' && isset($_SESSION['user_id']) && isset($course['created_by']) && $_SESSION['user_id'] == $course['created_by']): ?>
          <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning w-100 mt-3">Edit Course</a>
        <?php endif; ?>
        </ul>
<?php
// ...existing code...
?>
      </div>
    </div>
  </div>
  <?php if (isset($_GET['enroll_success']) && $_GET['enroll_success'] == 1): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>document.addEventListener('DOMContentLoaded',function(){toastr.success('Course registration successful!');});</script>
  <?php endif; ?>
  <script>
    function toggleChapter(idx) {
      var el = document.getElementById('chapter-lessons-' + idx);
      var icon = document.getElementById('icon-chapter-' + idx);
      if (el.style.display === 'none') {
        el.style.display = 'block';
        icon.textContent = '-';
      } else {
        el.style.display = 'none';
        icon.textContent = '+';
      }
    }
    function expandAllChapters() {
      <?php for ($i=0;$i<max(1,count($chapters));$i++): ?>
        var el = document.getElementById('chapter-lessons-<?php echo $i; ?>');
        var icon = document.getElementById('icon-chapter-<?php echo $i; ?>');
        if (el && icon) {
          el.style.display = 'block';
          icon.textContent = '-';
        }
      <?php endfor; ?>
    }
  </script>
  <?php else: ?>
    <div class="alert alert-danger mt-5">No course found.</div>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
