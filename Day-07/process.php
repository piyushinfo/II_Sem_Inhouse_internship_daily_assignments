<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Result</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f0f4f8; }
    .result-card {
      max-width: 560px;
      margin: 50px auto;
      background: white;
      border-radius: 14px;
      padding: 35px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .success-header {
      background-color: #d4edda;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      margin-bottom: 25px;
    }
    .success-header h3 { color: #155724; margin: 0; }
    .field-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
      font-size: 14px;
    }
    .field-row span:first-child { color: #666; }
    .field-row span:last-child  { font-weight: bold; color: #1a1a2e; }
    .btn-back {
      background-color: #1a1a2e;
      color: white;
      display: block;
      text-align: center;
      padding: 10px;
      border-radius: 8px;
      text-decoration: none;
      margin-top: 20px;
    }
    .btn-back:hover { background-color: #00d4ff; color: #1a1a2e; }
  </style>
</head>
<body>

<div class="result-card">

<?php

// ---- STEP 1: CHECK IF FORM WAS SUBMITTED ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<p class="text-center text-danger">Direct access not allowed. <a href="register.html">Go to form</a></p>';
    exit;
}

// ---- STEP 2: COLLECT POST DATA ----
// trim() removes extra spaces from both ends
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$branch  = trim($_POST['branch']  ?? '');
$phone   = trim($_POST['phone']   ?? '');
$gender  = trim($_POST['gender']  ?? '');
$course  = trim($_POST['course']  ?? '');
$address = trim($_POST['address'] ?? '');

// ---- STEP 3: VALIDATE ALL FIELDS ----
// Collect ALL errors into one array — show everything at once

$errors = [];

// Name: must not be empty AND must not contain numbers
if (empty($name)) {
    $errors[] = "Full name is required.";
} elseif (preg_match('/[0-9]/', $name)) {
    $errors[] = "Name must not contain numbers.";
}

// Email: must not be empty AND must be valid format
if (empty($email)) {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// Branch: must not be empty
if (empty($branch)) {
    $errors[] = "Branch is required.";
}

// Phone: must not be empty, must be exactly 10 digits, must be numeric
if (empty($phone)) {
    $errors[] = "Phone number is required.";
} elseif (!is_numeric($phone)) {
    $errors[] = "Phone number must contain digits only.";
} elseif (strlen($phone) !== 10) {
    $errors[] = "Phone number must be exactly 10 digits.";
}

// Gender: must be selected (Assignment)
if (empty($gender)) {
    $errors[] = "Please select your gender.";
}

// Course: must be selected
if (empty($course)) {
    $errors[] = "Please select a course.";
}

// Address: must not be empty AND minimum 10 characters (Assignment)
if (empty($address)) {
    $errors[] = "Address is required.";
} elseif (strlen($address) < 10) {
    $errors[] = "Address must be at least 10 characters long.";
}


// ---- STEP 4: SHOW ERRORS OR SUCCESS ----

if (count($errors) > 0) {

    // Show ALL errors together in a red Bootstrap alert box
    echo '<div class="alert alert-danger">';
    echo '<h5>⚠️ Please fix the following errors:</h5>';
    echo '<ul class="mb-0">';
    foreach ($errors as $err) {
        echo '<li>' . htmlspecialchars($err) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
    echo '<a href="register.html" class="btn-back">← Go Back & Fix Errors</a>';

} else {

    // All validations passed — show success confirmation card
?>

    <div class="success-header">
      <h3>✅ Registration Successful!</h3>
      <p style="color:#155724; margin:5px 0 0;">Welcome, <?= htmlspecialchars($name) ?>!</p>
    </div>

    <h6 class="mb-3" style="color:#1a1a2e;">📋 Submitted Details</h6>

    <div class="field-row">
      <span>Full Name</span>
      <span><?= htmlspecialchars($name) ?></span>
    </div>
    <div class="field-row">
      <span>Email</span>
      <span><?= htmlspecialchars($email) ?></span>
    </div>
    <div class="field-row">
      <span>Branch</span>
      <span><?= htmlspecialchars($branch) ?></span>
    </div>
    <div class="field-row">
      <span>Phone</span>
      <span><?= htmlspecialchars($phone) ?></span>
    </div>
    <div class="field-row">
      <span>Gender</span>
      <span><?= htmlspecialchars($gender) ?></span>
    </div>
    <div class="field-row">
      <span>Course</span>
      <span><?= htmlspecialchars($course) ?></span>
    </div>
    <div class="field-row">
      <span>Address</span>
      <span><?= htmlspecialchars($address) ?></span>
    </div>
    <div class="field-row">
      <span>Submitted On</span>
      <span><?= date("d M Y, H:i:s") ?></span>
    </div>

    <a href="register.html" class="btn-back">← Register Another Student</a>

<?php
}
?>

</div>

</body>
</html>
