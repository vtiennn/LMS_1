<?php
// instructor_students.php - Hiển thị danh sách sinh viên đã đăng ký các khóa học của giảng viên
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit();
}

require_once 'includes/db.php';
$instructor_id = $_SESSION['user_id'];
// Lấy tất cả khóa học của giảng viên
$stmt = $conn->prepare('SELECT id, title FROM courses WHERE created_by = ?');
$stmt->execute([$instructor_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete enrolled student
if (isset($_GET['delete_student']) && is_numeric($_GET['delete_student']) && is_numeric($_GET['course_id'])) {
    $student_id = intval($_GET['delete_student']);
    $course_id = intval($_GET['course_id']);
    $conn->prepare('DELETE FROM enrollments WHERE user_id = ? AND course_id = ?')->execute([$student_id, $course_id]);
    echo '<script>location.href="instructor_students.php?deleted=1";</script>';
    exit;
}

// Handle update enrolled student name/email
if (isset($_POST['update_student']) && is_numeric($_POST['student_id']) && is_numeric($_POST['course_id'])) {
    $student_id = intval($_POST['student_id']);
    $course_id = intval($_POST['course_id']);
    $new_name = trim($_POST['edit_name'] ?? '');
    $new_email = trim($_POST['edit_email'] ?? '');
    if ($new_name && $new_email) {
        $stmt = $conn->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        $stmt->execute([$new_name, $new_email, $student_id]);
        echo '<script>location.href="instructor_students.php?updated=1";</script>';
        exit;
    }
}

// Lấy danh sách sinh viên đã đăng ký từng khóa học
$students_by_course = [];
foreach ($courses as $course) {
    $stmt = $conn->prepare('SELECT u.id, u.name, u.email FROM enrollments e JOIN users u ON e.user_id = u.id WHERE e.course_id = ?');
    $stmt->execute([$course['id']]);
    $students_by_course[$course['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php include 'header.php'; ?>
<div class="container mt-5" style="padding: 50px;">
    <h2>Students Enrolled in My Courses</h2>
    <?php if (empty($courses)): ?>
        <div class="alert alert-info">You have not created any courses yet.</div>
    <?php else: ?>
        <?php foreach ($courses as $course): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <?php echo htmlspecialchars($course['title']); ?>
                </div>
                <div class="card-body">
                    <?php if (empty($students_by_course[$course['id']])): ?>
                        <div class="alert alert-warning mb-0">No students have enrolled in this course yet.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $edit_student_id = (isset($_GET['edit_student']) && isset($_GET['edit_course']) && intval($_GET['edit_course']) === intval($course['id'])) ? intval($_GET['edit_student']) : 0;
                                    foreach ($students_by_course[$course['id']] as $idx => $student):
                                        if ($edit_student_id === intval($student['id'])): ?>
                                            <tr>
                                                <form method="post">
                                                    <td><?php echo $idx+1; ?><input type="hidden" name="student_id" value="<?php echo $student['id']; ?>"><input type="hidden" name="course_id" value="<?php echo $course['id']; ?>"></td>
                                                    <td><input type="text" name="edit_name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required></td>
                                                    <td><input type="email" name="edit_email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required></td>
                                                    <td>
                                                        <button type="submit" name="update_student" class="btn btn-success btn-sm">Save</button>
                                                        <a href="instructor_students.php" class="btn btn-secondary btn-sm">Cancel</a>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td><?php echo $idx+1; ?></td>
                                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                <td>
                                                    <a href="instructor_students.php?edit_student=<?php echo $student['id']; ?>&edit_course=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="instructor_students.php?delete_student=<?php echo $student['id']; ?>&course_id=<?php echo $course['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this student from the course?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endif;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
