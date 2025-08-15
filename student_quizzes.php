<?php
// Trang xem và làm quiz cho sinh viên
include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'student') {
    header('Location: authentication-login.php');
    exit;
}

// Lấy danh sách quizzes
$quizzes = $conn->query('SELECT * FROM quizzes ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-5" style="margin-top: 30px;">
  <h2>Quiz List</h2>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Title</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($quizzes as $quiz): ?>
        <tr>
          <td><?php echo $quiz['id']; ?></td>
          <td><?php echo htmlspecialchars($quiz['title']); ?></td>
          <td>
            <a href="take_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-success">Take Quiz</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>
