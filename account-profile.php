
<?php
include 'header.php';
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: authentication-login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
// Handle inline edit
$edit_mode = isset($_POST['edit']) || isset($_POST['save']);
if (isset($_POST['save'])) {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $stmt = $conn->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
    $stmt->execute([$new_name, $new_email, $user_id]);
    // Reload updated data
    $edit_mode = false;
}
$stmt = $conn->prepare('SELECT name, email, role FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>


  <!--start page content-->
  <div class="page-content">

    <!--start breadcrumb-->
    <div class="py-4 border-bottom">
      <div class="container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <section class="section-padding">
      <div class="container">
        <div class="d-flex align-items-center px-3 py-2 border mb-4">
          <div class="text-start">
            <h4 class="mb-0 h4 fw-bold">Account - Profile</h4>
          </div>
        </div>
        <div class="btn btn-dark btn-ecomm d-xl-none position-fixed top-50 start-0 translate-middle-y" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbarFilter"><span><i class="bi bi-person me-2"></i>Account</span></div>
        <div class="row">
          <div class="col-12 col-xl-3 filter-column">
            <nav class="navbar navbar-expand-xl flex-wrap p-0">
              <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbarFilter" aria-labelledby="offcanvasNavbarFilterLabel">
                <div class="offcanvas-header">
                  <h5 class="offcanvas-title mb-0 fw-bold text-uppercase" id="offcanvasNavbarFilterLabel">Account</h5>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body account-menu">
                  <div class="list-group w-100 rounded-0">
                    <?php if (strtolower($user['role']) === 'instructor'): ?>
                      <a href="instructor_analytics.php" class="list-group-item"><i class="bi bi-house-door me-2"></i>Dashboard</a>
                    <?php else: ?>
                      <a href="dashboard_student.php" class="list-group-item"><i class="bi bi-house-door me-2"></i>Dashboard</a>
                    <?php endif; ?>
                    <a href="account-profile.php" class="list-group-item active"><i class="bi bi-person me-2"></i>Profile</a>
                  </div>
                </div>
              </div>
            </nav>
          </div>
          <div class="col-12 col-xl-9">
            <div class="card rounded-0">
              <div class="card-body p-lg-5">
                <h5 class="mb-0 fw-bold">Profile Details</h5>
                <hr>
                <form method="post">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td>Full Name</td>
                          <td>
                            <?php if ($edit_mode): ?>
                              <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            <?php else: ?>
                              <?php echo htmlspecialchars($user['name']); ?>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Email</td>
                          <td>
                            <?php if ($edit_mode): ?>
                              <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            <?php else: ?>
                              <?php echo htmlspecialchars($user['email']); ?>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Role</td>
                          <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="">
                    <?php if ($edit_mode): ?>
                      <button type="submit" name="save" class="btn btn-dark px-5">Save</button>
                      <a href="account-profile.php" class="btn btn-secondary ms-2">Cancel</a>
                    <?php else: ?>
                      <button type="submit" name="edit" class="btn btn-outline-dark btn-ecomm px-5"><i class="bi bi-pencil me-2"></i>Edit</button>
                    <?php endif; ?>
                  </div>
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