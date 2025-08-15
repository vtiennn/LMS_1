<?php

include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $conn->prepare('SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                header('Location: index.php?login_success=1');
                exit;
            } else {
                $message = 'Wrong password.';
            }
        } else {
            $message = 'Email does not exist.';
        }
    } else {
        $message = 'Please enter complete information.';
    }
}
?>

<?php
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    echo '<script>window.addEventListener("DOMContentLoaded",function(){toastr.success("Registration successful! Please login.");});</script>';
}
if ($message): ?>
    <div class="alert alert-danger text-center"><?php echo $message; ?></div>
<?php endif; ?>

<?php include 'header.php'; ?>

<!--end top header-->


<!--start page content-->
<div class="page-content">


  <!--start breadcrumb-->
  <div class="py-4 border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
          <li class="breadcrumb-item"><a href="javascript:;">Authentication</a></li>
          <li class="breadcrumb-item active" aria-current="page">Login</li>
        </ol>
      </nav>
    </div>
  </div>
  <!--end breadcrumb-->

  <section class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
          <div class="card rounded-0">
            <div class="card-body p-4">
              <h4 class="mb-0 fw-bold text-center">User Login</h4>
              <hr>
              <form method="post">
                <div class="row g-4">
                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control rounded-0" id="email" name="email" required>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control rounded-0" id="password" name="password" required>
                  </div>
                  <div class="col-12">
                    <a href="forgot_password.php" class="text-content btn bg-light rounded-0 w-100"><i
                        class="bi bi-lock me-2"></i>Forgot Password</a>
                  </div>
                  <div class="col-12">
                    <hr class="my-0">
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-dark rounded-0 btn-ecomm w-100">Login</button>
                  </div>
                  <div class="col-12 text-center">
                    <p class="mb-0 rounded-0 w-100">Don't have an account? <a href="authentication-register.php"
                        class="text-danger">Register</a></p>
                  </div>
                </div><!---end row-->
              </form>
            </div>
          </div>
        </div>
      </div><!--end row-->

    </div>
  </section>

</div>
<!--end page content-->

<?php include 'footer.php'; ?>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
    toastr.success('Registration successful! Please login.');
  <?php endif; ?>
  // toastr đăng nhập thành công sẽ được xử lý ở index.php sau khi chuyển hướng
});
</script>