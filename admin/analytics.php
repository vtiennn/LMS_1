<?php
// analytics.php - Admin analytics dashboard
include '../includes/db.php';
include 'admin_header.php';

// 1. Tổng quan
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_students = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$total_instructors = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'instructor'")->fetchColumn();
$total_courses = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
$total_assignments = $conn->query("SELECT COUNT(*) FROM assignments")->fetchColumn();
$total_submissions = $conn->query("SELECT COUNT(*) FROM submissions")->fetchColumn();

// 2. Đăng ký khóa học theo tháng (12 tháng gần nhất)
$enrollments_by_month = $conn->query("SELECT DATE_FORMAT(enrolled_at, '%Y-%m') as month, COUNT(*) as count FROM enrollments GROUP BY month ORDER BY month DESC LIMIT 12")->fetchAll(PDO::FETCH_ASSOC);
$enrollments_by_month = array_reverse($enrollments_by_month);

// 3. Tỉ lệ hoàn thành bài tập
$completion_rate = $total_assignments > 0 ? round(($total_submissions / $total_assignments) * 100, 2) : 0;

// 4. Top khóa học nhiều sinh viên đăng ký nhất
$top_courses = $conn->query("SELECT c.title, COUNT(e.id) as enroll_count FROM enrollments e JOIN courses c ON e.course_id = c.id GROUP BY e.course_id ORDER BY enroll_count DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// 5. Hoạt động sinh viên
$active_students = $conn->query("SELECT u.name, COUNT(s.id) as submissions FROM users u LEFT JOIN submissions s ON u.id = s.student_id WHERE u.role = 'student' GROUP BY u.id ORDER BY submissions DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$avg_grade = $conn->query("SELECT AVG(grade) FROM submissions WHERE grade IS NOT NULL")->fetchColumn();

// 6. Hiệu suất giảng viên
$instructor_stats = $conn->query("
    SELECT u.name,
           COUNT(a.id) as assignments,
           SUM(CASE WHEN s.id IS NOT NULL THEN 1 ELSE 0 END) as graded
    FROM users u
    LEFT JOIN courses c ON u.id = c.created_by
    LEFT JOIN assignments a ON c.id = a.course_id
    LEFT JOIN submissions s ON a.id = s.assignment_id AND s.grade IS NOT NULL
    WHERE u.role = 'instructor'
    GROUP BY u.id
    ORDER BY assignments DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>
    <h1 class="h3 mb-4 text-gray-800" style="margin-left: 20px;">Analytics Dashboard</h1>
    <div class="accordion mb-4" id="analyticsAccordion">
        <div class="card">
            <div class="card-header" id="headingOverview">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOverview" aria-expanded="true" aria-controls="collapseOverview">
                        User Overview
                    </button>
                </h2>
            </div>
            <div id="collapseOverview" class="collapse" aria-labelledby="headingOverview" data-parent="#analyticsAccordion">
                <div class="card-body d-flex justify-content-center align-items-center"><canvas id="overviewPie" style="max-width:75%;height:150px;"></canvas></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingEnroll">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseEnroll" aria-expanded="false" aria-controls="collapseEnroll">
                        Course Enrollments by Month
                    </button>
                </h2>
            </div>
            <div id="collapseEnroll" class="collapse" aria-labelledby="headingEnroll" data-parent="#analyticsAccordion">
                <div class="card-body d-flex justify-content-center align-items-center"><canvas id="enrollLine" style="max-width:75%;height:150px;"></canvas></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingCompletion">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseCompletion" aria-expanded="false" aria-controls="collapseCompletion">
                        Assignment Completion Rate
                    </button>
                </h2>
            </div>
            <div id="collapseCompletion" class="collapse" aria-labelledby="headingCompletion" data-parent="#analyticsAccordion">
                <div class="card-body d-flex justify-content-center align-items-center"><canvas id="completionDonut" style="max-width:75%;height:150px;"></canvas></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTopCourses">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseTopCourses" aria-expanded="false" aria-controls="collapseTopCourses">
                        Top 5 Most Enrolled Courses
                    </button>
                </h2>
            </div>
            <div id="collapseTopCourses" class="collapse" aria-labelledby="headingTopCourses" data-parent="#analyticsAccordion">
                <div class="card-body d-flex justify-content-center align-items-center"><canvas id="topCoursesBar" style="max-width:75%;height:150px;"></canvas></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingActiveStudents">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseActiveStudents" aria-expanded="false" aria-controls="collapseActiveStudents">
                        Top 5 Active Students
                    </button>
                </h2>
            </div>
            <div id="collapseActiveStudents" class="collapse" aria-labelledby="headingActiveStudents" data-parent="#analyticsAccordion">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center"><canvas id="activeStudentsBar" style="max-width:75%;height:150px;"></canvas></div>
                    <div class="mt-2">Average Grade: <b><?php echo $avg_grade ? round($avg_grade,2) : 'N/A'; ?></b></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingInstructor">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseInstructor" aria-expanded="false" aria-controls="collapseInstructor">
                        Top 5 Instructor Performance
                    </button>
                </h2>
            </div>
            <div id="collapseInstructor" class="collapse" aria-labelledby="headingInstructor" data-parent="#analyticsAccordion">
                <div class="card-body d-flex justify-content-center align-items-center"><canvas id="instructorBar" style="max-width:75%;height:150px;"></canvas></div>
            </div>
        </div>
    </div>
<!-- jQuery & Bootstrap JS for accordion -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js scripts -->
<script src="../admin/vendor/chart.js/Chart.min.js"></script>
<script>
// Chỉ khởi tạo chart khi accordion được mở
let chartInstances = {};
function renderChart(id, config) {
    if (!chartInstances[id]) {
        chartInstances[id] = new Chart(document.getElementById(id), config);
    }
}
$('#analyticsAccordion').on('show.bs.collapse', function (e) {
    const id = e.target.id;
    if (id === 'collapseOverview') {
        renderChart('overviewPie', {
            type: 'pie',
            data: {
                labels: ['Students', 'Instructors', 'Courses', 'Enrollments', 'Assignments'],
                datasets: [{
                    data: [<?php echo $total_students; ?>, <?php echo $total_instructors; ?>, <?php echo $total_courses; ?>, <?php echo $total_enrollments; ?>, <?php echo $total_assignments; ?>],
                    backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b']
                }]
            },
            options: {responsive:true}
        });
    }
    if (id === 'collapseEnroll') {
        renderChart('enrollLine', {
            type: 'line',
            data: {
                labels: [<?php foreach($enrollments_by_month as $row){echo "'{$row['month']}',";} ?>],
                datasets: [{
                    label: 'Enrollments',
                    data: [<?php foreach($enrollments_by_month as $row){echo $row['count'].",";} ?>],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78,115,223,0.1)',
                    fill: true
                }]
            },
            options: {responsive:true}
        });
    }
    if (id === 'collapseCompletion') {
        renderChart('completionDonut', {
            type: 'doughnut',
            data: {
                labels: ['Submitted','Not Submitted'],
                datasets: [{
                    data: [<?php echo $total_submissions; ?>, <?php echo max($total_assignments-$total_submissions,0); ?>],
                    backgroundColor: ['#1cc88a','#e74a3b']
                }]
            },
            options: {responsive:true}
        });
    }
    if (id === 'collapseTopCourses') {
        renderChart('topCoursesBar', {
            type: 'bar',
            data: {
                labels: [<?php foreach($top_courses as $row){echo "'".addslashes($row['title'])."',";} ?>],
                datasets: [{
                    label: 'Enrollments',
                    data: [<?php foreach($top_courses as $row){echo $row['enroll_count'].",";} ?>],
                    backgroundColor: '#36b9cc'
                }]
            },
            options: {responsive:true, indexAxis:'y'}
        });
    }
    if (id === 'collapseActiveStudents') {
        renderChart('activeStudentsBar', {
            type: 'bar',
            data: {
                labels: [<?php foreach($active_students as $row){echo "'".addslashes($row['name'])."',";} ?>],
                datasets: [{
                    label: 'Submissions',
                    data: [<?php foreach($active_students as $row){echo $row['submissions'].",";} ?>],
                    backgroundColor: '#f6c23e'
                }]
            },
            options: {responsive:true, indexAxis:'y'}
        });
    }
    if (id === 'collapseInstructor') {
        renderChart('instructorBar', {
            type: 'bar',
            data: {
                labels: [<?php foreach($instructor_stats as $row){echo "'".addslashes($row['name'])."',";} ?>],
                datasets: [
                    {
                        label: 'Assignments Given',
                        data: [<?php foreach($instructor_stats as $row){echo $row['assignments'].",";} ?>],
                        backgroundColor: '#4e73df'
                    },
                    {
                        label: 'Assignments Graded',
                        data: [<?php foreach($instructor_stats as $row){echo $row['graded'].",";} ?>],
                        backgroundColor: '#1cc88a'
                    }
                ]
            },
            options: {responsive:true, scales:{x:{stacked:false},y:{beginAtZero:true}}}
        });
    }
});
</script>
