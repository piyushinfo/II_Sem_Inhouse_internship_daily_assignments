<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Confirmation - Day 8</title>

  <!-- Bootstrap 5 CSS via CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

  <style>
    /* Small custom style for the gradient header on the confirmation card */
    .gradient-header {
      background: linear-gradient(135deg, #4e73df, #224abe);
      color: #fff;
    }
  </style>
</head>
<body class="bg-light">

  <!--
    Simple Bootstrap navbar shared across every page.
    $page is set BEFORE this file is included, so we can
    highlight the correct nav link using a PHP condition.
  -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
      <a class="navbar-brand" href="index.html">Scrum Digital Bootcamp</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">

          <li class="nav-item">
            <a class="nav-link <?php echo ($page === 'home') ? 'active fw-bold text-white' : ''; ?>"
               href="index.html">Register</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($page === 'confirmation') ? 'active fw-bold text-white' : ''; ?>"
               href="#">Confirmation</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="https://github.com/" target="_blank">GitHub</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
