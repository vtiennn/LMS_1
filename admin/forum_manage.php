<?php
// Trang quản lý forum cho admin
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && strtolower($_SESSION['role']) !== 'admin')) {
    header('Location: ../authentication-login.php');
    exit;
}
include '../includes/db.php';
// Xử lý xóa chủ đề (phải làm trước khi xuất bất kỳ HTML nào)
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM forums WHERE id = ?');
    $stmt->execute([$delete_id]);
    header('Location: forum_manage.php');
    exit;
}
include 'admin_header.php';
// Lấy danh sách các chủ đề/bài viết forum từ bảng forums
$topics = $conn->query('SELECT * FROM forums ORDER BY created_by DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-5">
  <h2>Forum Management</h2>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Title</th><th>Created By</th><th>Created At</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($topics as $topic): ?>
        <tr>
          <td><?php echo $topic['id']; ?></td>
          <td><?php echo htmlspecialchars($topic['title']); ?></td>
          <td><?php echo htmlspecialchars($topic['created_by']); ?></td>
          <td><?php echo $topic['created_by']; ?></td>
          <td>
            <a href="forum_topic_detail.php?id=<?php echo $topic['id']; ?>" class="btn btn-sm btn-info">View</a>
            <a href="forum_manage.php?delete=<?php echo $topic['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this topic?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include 'admin_footer.php'; ?>
