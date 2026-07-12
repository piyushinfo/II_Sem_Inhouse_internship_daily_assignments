<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Registration Portal - Day 9</title>

  <!-- Bootstrap 5 CSS via CDN -->
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
      <a class="navbar-brand" href="index.html">🎓 Student Registration Portal</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">

          <li class="nav-item">
            <a class="nav-link <?php echo ($page === 'home') ? 'active fw-bold text-white' : ''; ?>"
               href="index.html">Register</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($page === 'records') ? 'active fw-bold text-white' : ''; ?>"
               href="students.php">Student Records</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
