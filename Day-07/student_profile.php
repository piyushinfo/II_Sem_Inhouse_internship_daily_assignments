<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #1a1a2e; }
    .id-card {
      background: white;
      border-radius: 16px;
      padding: 30px;
      max-width: 420px;
      margin: 60px auto;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .id-card h2 { color: #1a1a2e; font-weight: bold; }
    .id-header {
      background-color: #1a1a2e;
      color: white;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 20px;
    }
    .id-header h4 { margin: 0; color: #00d4ff; }
    .id-header p  { margin: 0; font-size: 13px; color: #ccc; }
    .avatar {
      width: 80px; height: 80px;
      background-color: #00d4ff;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 36px;
      margin: 0 auto 15px;
    }
    .field-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #eee; }
    .field-row span:first-child { color: #666; font-size: 13px; }
    .field-row span:last-child  { font-weight: bold; color: #1a1a2e; font-size: 13px; }
    .badge-year { background-color: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
  </style>
</head>
<body>

<?php
  // All data comes from PHP variables — no hardcoded HTML text
  $name       = "Piyush Sharma";
  $college    = "SKIT Jaipur";
  $branch     = "Computer Science";
  $year       = "1st Year";
  $rollno     = "25ESKCS001";
  $bio        = "Passionate about web development, AI, and hackathons.";

  // Dynamic Academic Year — if month < 6 (June), use previous year range
  $month = (int) date("m");
  $y     = (int) date("Y");
  if ($month < 6) {
    $academicYear = ($y - 1) . "–" . $y;
  } else {
    $academicYear = $y . "–" . ($y + 1);
  }
?>

<div class="id-card">

  <div class="id-header">
    <p>Student Identity Card</p>
    <h4><?= $college ?></h4>
  </div>

  <div class="avatar">🧑‍💻</div>

  <h5 class="text-center mb-3"><?= $name ?></h5>

  <div class="field-row">
    <span>Roll Number</span>
    <span><?= $rollno ?></span>
  </div>
  <div class="field-row">
    <span>Branch</span>
    <span><?= $branch ?></span>
  </div>
  <div class="field-row">
    <span>Year of Study</span>
    <span><?= $year ?></span>
  </div>
  <div class="field-row">
    <span>Academic Year</span>
    <span class="badge-year"><?= $academicYear ?></span>
  </div>

  <p class="text-muted mt-3" style="font-size: 13px; text-align: center;">
    "<?= $bio ?>"
  </p>

</div>

</body>
</html>
