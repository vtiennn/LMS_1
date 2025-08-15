<?php
// forgot_password.php - Gửi email đặt lại mật khẩu
session_start();
require_once 'includes/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 giờ
        $stmt = $conn->prepare('UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?');
        $stmt->execute([$token, $expires, $user['id']]);
        // Gửi email (giả lập)
        $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/reset_password.php?token=' . $token;
        // TODO: Thay thế bằng gửi email thực tế
        $message = 'A password reset link has been sent to your email. Link: <a href="' . htmlspecialchars($reset_link) . '">' . htmlspecialchars($reset_link) . '</a>';
    } else {
        $message = 'Email does not exist.';
    }
}
?>
<?php include 'header.php'; ?>
<div class="container" style="max-width:500px; padding-top:100px;">
    <h2>Forgot Password</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Enter your email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Send password reset link</button>
    </form>
</div>
<?php include 'footer.php'; ?>
