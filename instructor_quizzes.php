<?php
// Trang quản lý quizzes cho instructor
include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit;
}


$message = '';
$edit_mode = false;
$edit_quiz = ['id' => '', 'title' => '', 'description' => ''];

// Xử lý xóa quiz
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $quiz_id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM quizzes WHERE id = ?');
    $stmt->execute([$quiz_id]);
    header('Location: instructor_quizzes.php');
    exit;
}

// Xử lý sửa quiz: lấy dữ liệu lên form
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
    $edit_mode = true;
    $quiz_id = intval($_GET['edit']);
    $stmt = $conn->prepare('SELECT * FROM quizzes WHERE id = ?');
    $stmt->execute([$quiz_id]);
    $edit_quiz = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$edit_quiz) {
        $edit_quiz = ['id' => '', 'title' => '', 'description' => ''];
        $edit_mode = false;
    }
}

// Xử lý submit form thêm/sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $quiz_id = intval($_POST['quiz_id'] ?? 0);
    if ($title) {
        if ($quiz_id > 0) {
            // Sửa quiz
            $stmt = $conn->prepare('UPDATE quizzes SET title = ?, description = ? WHERE id = ?');
            $stmt->execute([$title, $description, $quiz_id]);
            $message = 'Quiz updated successfully!';
            // Sau khi sửa, reload lại trang (có thể chuyển hướng sang quản lý câu hỏi nếu muốn)
            header('Location: instructor_quizzes.php?edit=' . $quiz_id . '&msg=1');
            exit;
        } else {
            // Thêm quiz
            $stmt = $conn->prepare('INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)');
            $stmt->execute([$title, $description, $_SESSION['user_id']]);
            $new_quiz_id = $conn->lastInsertId();
            // Sau khi thêm quiz, chuyển sang trang thêm câu hỏi cho quiz này
            header('Location: manage_questions.php?quiz_id=' . $new_quiz_id);
            exit;
        }
    } else {
            $message = 'Please enter quiz title.';
        $edit_quiz = ['id' => $quiz_id, 'title' => $title, 'description' => $description];
        $edit_mode = $quiz_id > 0;
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 1) {
    $message = 'Operation successful!';
}

// Lấy danh sách quizzes
$quizzes = $conn->query('SELECT * FROM quizzes ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-5" style="margin-top: 50px; max-width:900px;">
  <h2>Quizzes Management</h2>
  <?php if ($message): ?><div class="alert alert-info"> <?php echo $message; ?> </div><?php endif; ?>
  <form method="post" class="mb-4">
    <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($edit_quiz['id']); ?>">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_quiz['title']); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control"><?php echo htmlspecialchars($edit_quiz['description']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-<?php echo $edit_mode ? 'warning' : 'primary'; ?>">
      <?php echo $edit_mode ? 'Update Quiz' : 'Add New Quiz'; ?>
    </button>
    <?php if ($edit_mode): ?>
      <a href="instructor_quizzes.php" class="btn btn-secondary">Cancel</a>
      <a href="manage_questions.php?quiz_id=<?php echo $edit_quiz['id']; ?>" class="btn btn-info">Manage Questions</a>
    <?php endif; ?>
  </form>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Title</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($quizzes as $quiz): ?>
        <tr>
          <td><?php echo $quiz['id']; ?></td>
          <td><?php echo htmlspecialchars($quiz['title']); ?></td>
          <td>
            <a href="instructor_quizzes.php?edit=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="instructor_quizzes.php?delete=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirm delete?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>
