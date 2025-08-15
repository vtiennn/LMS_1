<?php
// Trang làm quiz cho sinh viên
include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'student') {
    header('Location: authentication-login.php');
    exit;
}
$quiz_id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    echo '<div class="alert alert-danger">Quiz does not exist.</div>';
    exit;
}
// Lấy câu hỏi và đáp án
$stmt = $conn->prepare('SELECT * FROM quiz_questions WHERE quiz_id = ?');
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$answers = [];
foreach ($questions as $q) {
    $stmt2 = $conn->prepare('SELECT * FROM quiz_answers WHERE question_id = ?');
    $stmt2->execute([$q['id']]);
    $answers[$q['id']] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
// Xử lý nộp bài
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    foreach ($questions as $q) {
        $selected = $_POST['question_' . $q['id']] ?? null;
        foreach ($answers[$q['id']] as $ans) {
            if ($ans['is_correct'] && $selected == $ans['id']) {
                $score++;
            }
        }
    }
    $result = "You got $score/" . count($questions) . " questions correct.";
}
?>
<div class="container py-5" style="max-width:800px;">
  <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
  <p><?php echo nl2br(htmlspecialchars($quiz['description'])); ?></p>
  <?php if ($result): ?><div class="alert alert-info"><?php echo $result; ?></div><?php endif; ?>
  <form method="post">
    <?php foreach ($questions as $idx => $q): ?>
      <div class="mb-3">
        <label><b>Question <?php echo $idx+1; ?>:</b> <?php echo htmlspecialchars($q['question_text']); ?></label>
        <?php foreach ($answers[$q['id']] as $ans): ?>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="question_<?php echo $q['id']; ?>" value="<?php echo $ans['id']; ?>" id="ans_<?php echo $ans['id']; ?>" required>
            <label class="form-check-label" for="ans_<?php echo $ans['id']; ?>">
              <?php echo htmlspecialchars($ans['answer_text']); ?>
            </label>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-success">Submit</button>
    <a href="student_quizzes.php" class="btn btn-secondary">Back</a>
  </form>
</div>
<?php include 'footer.php'; ?>
