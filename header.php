<!doctype html>
<html lang="en" class="light-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x32.webp" type="image/webp" />
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" type="text/css" href="assets/plugins/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="assets/plugins/slick/slick-theme.css" />
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/dark-theme.css" rel="stylesheet">
  <title>Learning Management System</title>
</head>

<body>
  <header class="top-header">
    <nav class="navbar navbar-expand-xl w-100 navbar-dark container gap-3">
      <a class="navbar-brand d-none d-xl-inline" href="index.php"><img src="assets/images/logo.webp" class="logo-img" alt=""></a>
      <a class="mobile-menu-btn d-inline d-xl-none" href="javascript:;" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasNavbar">
        <i class="bi bi-list"></i>
      </a>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
        <div class="offcanvas-header">
          <div class="offcanvas-logo"><img src="assets/images/logo.webp" class="logo-img" alt="">
          </div>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body primary-menu">
<?php
            if (session_status() === PHP_SESSION_NONE) session_start();
            // If admin, redirect to admin/index.php (unless already in admin/)
            if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') {
              $current = $_SERVER['REQUEST_URI'];
              if (strpos($current, '/admin/') === false && strpos($current, 'admin/index.php') === false) {
                header('Location: admin/index.php');
                exit;
              }
            }
            // Debug: Print role for checking
            // echo '<!-- ROLE: ' . (isset($_SESSION['role']) ? $_SESSION['role'] : 'none') . ' -->';
          ?>
          <ul class="navbar-nav justify-content-start flex-grow-1 gap-1">
            <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'instructor'): ?>
              <li class="nav-item"><a class="nav-link" href="instructor_courses.php">My Courses</a></li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="contentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">CREATE</a>
                <ul class="dropdown-menu" aria-labelledby="contentDropdown">
                  <li><a class="dropdown-item" href="create_course.php">Create Course</a></li>
                  <li><a class="dropdown-item" href="create_assignment.php">Create Assignment</a></li>
                </ul>
              </li>
              <li class="nav-item"><a class="nav-link" href="instructor_quizzes.php">Quizzes</a></li>
              <li class="nav-item"><a class="nav-link" href="instructor_submissions.php">Grading</a></li>
              <li class="nav-item"><a class="nav-link" href="instructor_students.php">Students</a></li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="contentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">CONTENT MANAGEMENT</a>
                <ul class="dropdown-menu" aria-labelledby="contentDropdown">
                  <li><a class="dropdown-item" href="instructor_materials.php">Course Materials</a></li>
                  <li><a class="dropdown-item" href="forum.php">Forum</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="analyticsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">ANALYTICS</a>
                <ul class="dropdown-menu" aria-labelledby="analyticsDropdown">
                  <li><a class="dropdown-item" href="instructor_analytics.php">Course Analytics</a></li>
                  <li><a class="dropdown-item" href="instructor_reports.php">Reports</a></li>
                </ul>
              </li>
            <?php elseif (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'student'): ?>
              <li class="nav-item"><a class="nav-link" href="dashboard_student.php">My Courses</a></li>
              <li class="nav-item"><a class="nav-link" href="student_assignments.php">Assignments</a></li>
              <li class="nav-item"><a class="nav-link" href="student_quizzes.php">Quizzes</a></li>
              <li class="nav-item"><a class="nav-link" href="student_grades.php">Grades</a></li>
              <li class="nav-item"><a class="nav-link" href="forum.php">Forum</a></li>
            <?php else: ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>
      <ul class="navbar-nav secondary-menu flex-row">

        <?php
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user_id'])) {
        ?>
            <li class="nav-item">
              <a class="nav-link" href="account-profile.php">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link" href="authentication-login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="authentication-register.php">Register</a>
            </li>
        <?php } ?>
      </ul>
    </nav>
  </header>