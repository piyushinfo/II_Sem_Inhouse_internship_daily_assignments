<?php
// Bring in our reusable grade/date/greeting functions
include 'functions.php';

// $page tells header.php which nav link to highlight
$page = "confirmation";

// -----------------------------
// STEP 1: Read the submitted data
// -----------------------------
// $_POST is a superglobal array holding everything sent from index.html
$name    = $_POST['student_name'] ?? '';
$email   = $_POST['email'] ?? '';
$cgpa    = $_POST['cgpa'] ?? '';
$branch  = $_POST['branch'] ?? '';
$college = $_POST['college'] ?? '';

// -----------------------------
// STEP 2: Validate the data
// -----------------------------
// We collect every problem in an array so we can show ALL errors at once,
// instead of stopping at the first one.
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
} elseif (preg_match('/[0-9]/', $name)) {
    $errors[] = "Name must not contain numbers.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

if ($cgpa === '' || !is_numeric($cgpa) || $cgpa < 0 || $cgpa > 10) {
    $errors[] = "CGPA must be a number between 0 and 10.";
}

if (empty($branch)) {
    $errors[] = "Please select a branch.";
}

if (empty($college)) {
    $errors[] = "College name is required.";
}

// Now that validation is done, load the shared header (navbar etc.)
include 'header.php';
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7">

      <?php if (count($errors) > 0): ?>

        <!-- ================= ERROR STATE ================= -->
        <div class="alert alert-danger">
          <h5 class="alert-heading">⚠️ Please fix the following:</h5>
          <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <a href="index.html" class="btn btn-outline-primary">← Back to Form</a>

      <?php else: ?>

        <!-- ================= SUCCESS STATE ================= -->
        <?php
          // htmlspecialchars() escapes user input before we print it,
          // so the page can't be broken (or attacked) by odd characters.
          $safeName    = htmlspecialchars($name);
          $safeEmail   = htmlspecialchars($email);
          $safeBranch  = htmlspecialchars($branch);
          $safeCollege = htmlspecialchars($college);
          $cgpaValue   = (float) $cgpa;

          // Call our reusable functions from functions.php
          $grade    = calculateGrade($cgpaValue);
          $today    = getFormattedDate();
          $greeting = getGreeting();
        ?>

        <div class="card shadow-sm">
          <div class="card-header gradient-header text-center py-4">
            <div style="font-size: 3rem;">🧑‍🎓</div>
            <h3 class="mb-0"><?php echo $greeting; ?>, <?php echo $safeName; ?>!</h3>
            <small><?php echo $today; ?></small>
          </div>

          <div class="card-body">
            <h5 class="card-title mb-3">Registration Confirmed ✅</h5>

            <table class="table table-borderless">
              <tr>
                <th scope="row">Name</th>
                <td><?php echo $safeName; ?></td>
              </tr>
              <tr>
                <th scope="row">Email</th>
                <td><?php echo $safeEmail; ?></td>
              </tr>
              <tr>
                <th scope="row">Branch</th>
                <td><?php echo $safeBranch; ?></td>
              </tr>
              <tr>
                <th scope="row">College</th>
                <td><?php echo $safeCollege; ?></td>
              </tr>
              <tr>
                <th scope="row">CGPA</th>
                <td><?php echo $cgpaValue; ?></td>
              </tr>
            </table>

            <!-- Alert color changes dynamically based on the grade band -->
            <div class="alert <?php echo $grade['class']; ?> text-center fw-bold">
              Performance: <?php echo $grade['label']; ?>
            </div>

            <a href="index.html" class="btn btn-primary w-100">Register Another Student</a>
          </div>
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>
