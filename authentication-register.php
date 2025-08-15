<?php
include 'header.php';
include 'includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    // Chỉ cho phép đăng ký role student
    $role = 'student';
    if ($name && $email && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        // Kiểm tra email đã tồn tại chưa
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = 'Email already exists.';
        } else {
            $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$name, $email, $hashed, $role])) {
                header('Location: authentication-login.php?registered=1');
                exit;
            } else {
                $message = 'An error occurred during registration.';
            }
        }
    } else {
        $message = 'Please enter all required information.';
    }
}
?>

<?php if ($message): ?>
    <div class="alert alert-info text-center"><?php echo $message; ?></div>
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
          <li class="breadcrumb-item active" aria-current="page">Register</li>
        </ol>
      </nav>
    </div>
  </div>
  <!--end breadcrumb-->

  <section class="section-padding">
    <div class="container">

      <div class="row">
        <div class="col-12 col-lg-6 col-xl-5 col-xxl-5 mx-auto">
          <div class="card rounded-0">
            <div class="card-body p-4">
              <h4 class="mb-0 fw-bold text-center">Registration</h4>
              <hr>
              <form method="post">
                <div class="row g-4">
                  <div class="col-12">
                    <label for="fullname" class="form-label">Full name</label>
                    <input type="text" class="form-control rounded-0" id="fullname" name="fullname" required>
                  </div>
                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control rounded-0" id="email" name="email" required>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control rounded-0" id="password" name="password" required>
                  </div>
                  <!-- Ẩn lựa chọn role, mặc định là student -->
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" required>
                      <label class="form-check-label" for="flexCheckDefault">
                        I agree to the Terms of Service
                      </label>
                    </div>
                  </div>
                  <div class="col-12">
                    <hr class="my-0">
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-dark rounded-0 btn-ecomm w-100">Register</button>
                  </div>
                  <div class="col-12 text-center">
                    <p class="mb-0 rounded-0 w-100">Already have an account? <a href="authentication-login.php" class="text-danger">Login</a></p>
                  </div>
                </div><!---end row-->
              </form>
            </div>
          </div>
        </div>
      </div><!--end row-->

    </div>
  </section>
  <!--start product details-->


</div>
<!--end page content-->

<?php include 'footer.php'; ?>