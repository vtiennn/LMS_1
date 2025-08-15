<?php
// reset_password.php - Đặt lại mật khẩu mới
session_start();
require_once 'includes/db.php';
$message = '';
$token = $_GET['token'] ?? '';
if ($token) {
    $stmt = $conn->prepare('SELECT id, reset_token_expires FROM users WHERE reset_token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && strtotime($user['reset_token_expires']) > time()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            if ($password === $password2 && strlen($password) >= 6) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?');
                $stmt->execute([$hash, $user['id']]);
                $message = 'Password reset successful! You can <a href="authentication-login.php">login</a>.';
            } else {
                $message = 'Passwords do not match or are too short.';
            }
        }
    } else {
        $message = 'Invalid or expired link.';
    }
} else {
    $message = 'Missing token.';
}
?>
<?php include 'header.php'; ?>
<div class="container" style="max-width:500px; padding-top:100px;">
    <h2>Reset Password</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($token && isset($user) && $user && strtotime($user['reset_token_expires']) > time() && empty($message)): ?>
    <form method="post">
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" required minlength="6">
        </div>
        <div class="mb-3">
            <label for="password2" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password2" name="password2" required minlength="6">
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
