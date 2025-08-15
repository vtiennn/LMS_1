<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: authentication-login.php');
    exit;
}
include 'header.php';
include 'includes/db.php';

// Lấy danh sách khóa học
$stmt = $conn->prepare('SELECT id, title FROM courses');
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Thêm chủ đề mới
$message = '';
if (isset($_POST['add_topic']) && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['course_id'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $course_id = intval($_POST['course_id']);
    $stmt = $conn->prepare('INSERT INTO forums (course_id, title, created_by) VALUES (?, ?, ?)');
    $stmt->execute([$course_id, $title, $_SESSION['user_id']]);
    header('Location: forum.php');
    exit();
}
// Thêm bình luận
if (isset($_POST['add_comment']) && !empty($_POST['comment']) && isset($_POST['topic_id'])) {
    $comment = trim($_POST['comment']);
    $topic_id = intval($_POST['topic_id']);
    $stmt = $conn->prepare('INSERT INTO messages (forum_id, sender_id, message_text, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$topic_id, $_SESSION['user_id'], $comment]);
    header('Location: forum.php#topic-' . $topic_id);
    exit();
}
// Lấy danh sách chủ đề (theo khóa học)
$stmt = $conn->prepare('SELECT f.*, c.title as course_title, u.name as author_name, u.role as author_role FROM forums f JOIN courses c ON f.course_id = c.id JOIN users u ON f.created_by = u.id ORDER BY f.id DESC');
$stmt->execute();
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Lấy bình luận cho từng chủ đề
$comments_by_topic = [];
foreach ($topics as $topic) {
    $stmt = $conn->prepare('SELECT m.*, u.name, u.role FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.forum_id = ? ORDER BY m.created_at ASC');
    $stmt->execute([$topic['id']]);
    $comments_by_topic[$topic['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>



<div class="container mt-5" style="padding: 50px; max-width:900px;">
    <h2><i class="bi bi-journal-bookmark"></i> Forum</h2>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">➕ Add New Discussion Topic</div>
        <div class="card-body">
            <form method="post">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select name="course_id" class="form-select" required>
                            <option value="">📘 Select Course</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?php echo $c['id']; ?>">📘 <?php echo htmlspecialchars($c['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="title" class="form-control" placeholder="Topic Title" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="content" class="form-control" placeholder="Short Description" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" name="add_topic" class="btn btn-primary w-100">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if (empty($topics)): ?>
        <div class="alert alert-info">No topics yet. Create the first topic!</div>
    <?php else: ?>
        <?php $current_course = null; ?>
        <?php foreach ($topics as $topic): ?>
            <?php if ($current_course !== $topic['course_id']): $current_course = $topic['course_id']; ?>
                <div class="mt-4 mb-2"><span style="font-size:1.2rem;">📘 [Course] <b><?php echo htmlspecialchars($topic['course_title']); ?></b></span></div>
            <?php endif; ?>
            <div class="card mb-3" id="topic-<?php echo $topic['id']; ?>">
                <div class="card-header bg-light">
                    <span style="font-size:1.1rem;">↳ 📌 Forum: “<?php echo htmlspecialchars($topic['title']); ?>”</span>
                </div>
                <div class="card-body">
                    <div class="mb-2"> <?php echo isset($topic['content']) ? nl2br(htmlspecialchars($topic['content'])) : ''; ?> </div>
                    <div class="mb-2">
                        <b>Comments:</b>
                        <?php if (empty($comments_by_topic[$topic['id']])): ?>
                            <div class="text-muted mb-2">No comments yet.</div>
                        <?php else: ?>
                            <ul class="list-group mb-2">
                                <?php foreach ($comments_by_topic[$topic['id']] as $cmt): ?>
                                    <li class="list-group-item">
                                        <?php if ($cmt['role'] === 'instructor'): ?>
                                            <span title="Instructor">👨‍🏫</span>
                                        <?php else: ?>
                                            <span title="Student">👨‍🎓</span>
                                        <?php endif; ?>
                                        <b><?php echo htmlspecialchars($cmt['name']); ?>:</b> <?php echo nl2br(htmlspecialchars($cmt['message_text'])); ?>
                                        <span class="text-muted" style="font-size:0.9rem; float:right;">(<?php echo $cmt['created_at']; ?>)</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <form method="post" class="d-flex gap-2 align-items-center">
                            <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                            <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required>
                            <button type="submit" name="add_comment" class="btn btn-secondary">Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="alert alert-info mt-2"><?php echo $message; ?></div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
