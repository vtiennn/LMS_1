<?php
// Trang quản lý câu hỏi cho instructor
include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'instructor') {
    header('Location: authentication-login.php');
    exit;
}
$quiz_id = intval($_GET['quiz_id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    echo '<div class="alert alert-danger">Quiz does not exist.</div>';
    exit;
}
// Lấy danh sách câu hỏi
$stmt = $conn->prepare('SELECT * FROM quiz_questions WHERE quiz_id = ?');
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-5" style="max-width:900px;">
  <h2>Manage Questions for Quiz: <?php echo htmlspecialchars($quiz['title']); ?></h2>
  <a href="add_question.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-primary mb-3">Add Question</a>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Question Content</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($questions as $q): ?>
        <tr>
          <td><?php echo $q['id']; ?></td>
          <td><?php echo htmlspecialchars($q['question_text']); ?></td>
          <td>
            <a href="edit_question.php?id=<?php echo $q['id']; ?>&quiz_id=<?php echo $quiz_id; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_question.php?id=<?php echo $q['id']; ?>&quiz_id=<?php echo $quiz_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirm delete?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="instructor_quizzes.php" class="btn btn-secondary">Back</a>
</div>
<?php include 'footer.php'; ?>
