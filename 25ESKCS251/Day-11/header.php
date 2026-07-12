<?php
// header.php can be included from BOTH protected pages (which already
// called session_start() via auth_check.php) and public pages (which
// haven't). This check avoids the "session already started" warning
// either way.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Registration Portal - Day 11</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body class="bg-light">

  <!--
    $page is set BEFORE this file is included (in each PHP page),
    so we can highlight the correct nav link with a PHP condition.
  -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
      <a class="navbar-brand" href="<?php echo $isLoggedIn ? 'dashboard.php' : 'login.php'; ?>">
        🎓 Student Registration Portal
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">

          <?php if ($isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page === 'dashboard') ? 'active fw-bold text-white' : ''; ?>"
                 href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page === 'records') ? 'active fw-bold text-white' : ''; ?>"
                 href="students.php">Student Records</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($page === 'home') ? 'active fw-bold text-white' : ''; ?>"
                 href="index.html">Register Student</a>
            </li>
          <?php endif; ?>

        </ul>

        <!-- Bonus: show logged-in user's name + logout button in the navbar -->
        <?php if ($isLoggedIn): ?>
          <span class="navbar-text text-white me-3">
            👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?>
          </span>
          <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
        <?php endif; ?>

      </div>
    </div>
  </nav>
