<?php
// Module 4: guard clause — must be the very first thing that runs
include 'auth_check.php';
include 'db_connect.php';

$page = "dashboard";

// Quick stats for the dashboard cards
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'];

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
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h1 class="display-6"><?php echo $totalStudents; ?></h1>
          <p class="text-muted mb-0">Total Students</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h1 class="display-6">🔒</h1>
          <p class="text-muted mb-0">Your account is secure</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h1 class="display-6"><?php echo htmlspecialchars($_SESSION['user_email']); ?></h1>
          <p class="text-muted mb-0">Logged in as</p>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="students.php" class="btn btn-primary">📋 View Student Records</a>
    <a href="index.html" class="btn btn-outline-primary">+ Register New Student</a>
  </div>

</div>
