<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f0f4f8; }
    .card { border-radius: 12px; }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card shadow p-4" style="max-width: 500px; margin: auto;">
    <h2 class="text-center mb-4" style="color: #1a1a2e;">👋 Welcome!</h2>

    <?php
      // PHP Variables
      $name     = "Piyush Sharma";
      $language = "JavaScript";
      $date     = date("Y-m-d");
      $time     = date("H:i:s");
      $ip       = $_SERVER['REMOTE_ADDR'];
    ?>

    <p><strong>Name:</strong> <?= $name ?></p>
    <p><strong>Favourite Language:</strong> <?= $language ?></p>
    <p><strong>Current Date:</strong> <?= $date ?></p>
    <p><strong>Current Time:</strong> <?= $time ?></p>
    <hr>
    <p class="text-muted">🌐 You are visiting from: <strong><?= $ip ?></strong></p>
  </div>
</div>

</body>
</html>
