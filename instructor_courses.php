<?php
// instructor_courses.php - Hiển thị các khóa học mà giảng viên đã tạo
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit();
}

require_once 'includes/db.php';
$instructor_id = $_SESSION['user_id'];

// Xử lý xóa khóa học
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course_id'])) {
    $delete_id = intval($_POST['delete_course_id']);
    // Chỉ cho phép xóa nếu là instructor tạo ra
    $stmt = $conn->prepare('DELETE FROM courses WHERE id = ? AND created_by = ?');
    $stmt->execute([$delete_id, $instructor_id]);
    // Xóa xong load lại danh sách
    header('Location: instructor_courses.php');
    exit();
}

$stmt = $conn->prepare('SELECT id, title, description FROM courses WHERE created_by = ? ORDER BY id DESC');
$stmt->execute([$instructor_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'header.php'; ?>
<div class="container mt-5" style="padding: 50px;">
    <h2>My Courses</h2>
    <?php if (empty($courses)): ?>
        <div class="alert alert-info">You have not created any courses yet.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                            <a href="course_details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">View Details</a>
                            <form method="post" action="" style="display:inline-block; margin-left:8px;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                <input type="hidden" name="delete_course_id" value="<?php echo $course['id']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
