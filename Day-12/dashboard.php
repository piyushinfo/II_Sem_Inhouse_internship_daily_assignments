<?php
// Module 4: guard clause — must be the very first thing that runs
include 'auth_check.php';
include 'db_connect.php';

$page = "dashboard";

include 'functions.php';

// Quick stats for the dashboard cards
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'];

// Day 12, Module 3: how many students were added in the last 7 days
$newThisWeek = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM students WHERE date_registered >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
))['total'];

// Day 12, Module 3 Bonus: last 5 students added, for the Recent Registrations widget
$recentStudents = mysqli_fetch_all(
    mysqli_query($conn, "SELECT * FROM students ORDER BY date_registered DESC LIMIT 5"),
    MYSQLI_ASSOC
);

// Bonus: fetch this user's last login time from the database
// (separate from $_SESSION['login_time'], which is just for THIS session)
$stmt = mysqli_prepare($conn, "SELECT last_login FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$lastLoginRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$lastLogin = $lastLoginRow['last_login'] ? date("M j, Y g:i A", strtotime($lastLoginRow['last_login'])) : "This is your first login!";

include 'header.php';
?>

<div class="container pb-5">

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h2 class="mb-1">👋 Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
      <p class="text-muted mb-0">
        Session started at <?php echo htmlspecialchars($_SESSION['login_time']); ?> ·
        Last login: <?php echo htmlspecialchars($lastLogin); ?>
      </p>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h1 class="display-6"><?php echo $totalStudents; ?></h1>
          <p class="text-muted mb-0">Total Students</p>
        </div>
      </div>
    </div>
    <!-- Day 12, Module 3: "Added This Week" stat card -->
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h1 class="display-6 text-success"><?php echo $newThisWeek; ?></h1>
          <p class="text-muted mb-0">Added This Week</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h1 class="display-6">🔒</h1>
          <p class="text-muted mb-0">Your account is secure</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h6 class="mb-1 text-truncate"><?php echo htmlspecialchars($_SESSION['user_email']); ?></h6>
          <p class="text-muted mb-0">Logged in as</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-8">
      <div class="d-flex gap-2">
        <a href="students.php" class="btn btn-primary"><i class="bi bi-clipboard-data"></i> View Student Records</a>
        <a href="index.html" class="btn btn-outline-primary"><i class="bi bi-person-plus-fill"></i> Register New Student</a>
      </div>
    </div>

    <!-- Day 12, Module 3 Bonus: Recent Registrations widget -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-3"><i class="bi bi-clock-history"></i> Recent Registrations</h6>
          <?php if (count($recentStudents) === 0): ?>
            <p class="text-muted small mb-0">No students registered yet.</p>
          <?php else: ?>
            <ul class="list-unstyled mb-0">
              <?php foreach ($recentStudents as $s): ?>
                <li class="d-flex align-items-center gap-2 mb-2">
                  <img src="<?php echo htmlspecialchars(getStudentPhoto($s['photo'])); ?>"
                       class="student-avatar" style="width:32px;height:32px;" alt="<?php echo htmlspecialchars($s['name']); ?>" />
                  <div>
                    <div class="small fw-semibold"><?php echo htmlspecialchars($s['name']); ?></div>
                    <div class="small text-muted"><?php echo htmlspecialchars($s['branch']); ?></div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</div>
