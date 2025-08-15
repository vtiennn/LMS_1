

<?php include 'header.php'; ?>
<?php
include 'includes/db.php';
$stmt = $conn->prepare('SELECT id, title, description, thumbnail FROM courses ORDER BY id DESC LIMIT 20');
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Nếu muốn phân loại miễn phí/trả phí, cần có cột price. Hiện tại chỉ lấy tất cả khoá học.
$free_courses = $courses;
$paid_courses = [];
?>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  <?php if (isset($_GET['login_success']) && $_GET['login_success'] == 1): ?>
    toastr.success('Login successful!');
  <?php endif; ?>
});
</script>

  <!--end top header-->
  
  <!--start page content-->
  <div class="page-content">
    <!--start tabular product-->
    <section class="product-tab-section section-padding bg-light">
      <div class="container">
        <!-- Courses Section -->
        <div class="text-center pb-3 mt-5">
          <h3 class="mb-0 h3 fw-bold">Courses</h3>
        </div>
        <hr>
        <div class="tab-content tabular-product">
          <div class="tab-pane fade show active" id="free-courses">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5 g-4">
              <?php if ($free_courses): foreach ($free_courses as $course): ?>
                <div class="col">
                  <div class="card" style="border-radius: 12px;">
                    <div class="position-relative overflow-hidden">
                      <div class="product-options d-flex align-items-center justify-content-center gap-2 mx-auto position-absolute bottom-0 start-0 end-0"></div>
                      <a href="course_details.php?id=<?php echo $course['id']; ?>">
                        <img src="<?php echo !empty($course['thumbnail']) ? htmlspecialchars($course['thumbnail']) : 'assets/images/new-arrival/01.webp'; ?>" class="card-img-top" alt="Course Thumbnail">
                      </a>
                    </div>
                    <div class="card-body" style="height: 100px; display: flex; flex-direction: column; justify-content: center; border-radius: 0 0 12px 12px;">
                      <div class="product-info text-center">
                        <h6 class="mb-1 fw-bold product-name text-start">
                          <a href="course_details.php?id=<?php echo $course['id']; ?>" class="text-dark text-decoration-none text-start" style="display:block;">
                            <?php echo htmlspecialchars($course['title']); ?>
                          </a>
                        </h6>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; else: ?>
                <div class="col"><div class="alert alert-info">No courses available.</div></div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--end tabular product-->

  </div>
  <!--end page content-->

<?php include 'footer.php'; ?>

  <!-- JavaScript files -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/plugins/slick/slick.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/index.js"></script>
  <script src="assets/js/loader.js"></script>

</body>

</html>