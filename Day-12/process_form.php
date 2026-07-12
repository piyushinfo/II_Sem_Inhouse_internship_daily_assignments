<?php
// db_connect.php gives us $conn — the live MySQL connection
include 'db_connect.php';
include 'functions.php';

$page = "home"; // used by header.php to highlight the "Register" nav link

$errors = [];
$successMessage = "";
$studentCount = null;
$grade = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // -----------------------------
    // STEP 1: Read the submitted data
    // -----------------------------
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $cgpa    = $_POST['cgpa'] ?? '';
    $branch  = $_POST['branch'] ?? '';
    $course  = $_POST['course'] ?? '';
    $address = $_POST['address'] ?? '';

    // Day 12, Module 1: real photo upload handling.
    // $photo stays null (no photo) unless a valid file was uploaded —
    // getStudentPhoto() in functions.php falls back to the default
    // avatar whenever this is null.
    $photo = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Something went wrong uploading the photo. Please try again.";
        } elseif (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Photo must be a JPG, PNG, or GIF file.";
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "Photo is too large (max 2MB).";
        } else {
            // Unique filename so two students' uploads never collide.
            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('student_') . '.' . $ext;

            if (move_uploaded_file($file['tmp_name'], 'uploads/' . $filename)) {
                $photo = $filename;
            } else {
                $errors[] = "Could not save the uploaded photo.";
            }
        }
    }

    // -----------------------------
    // STEP 2: Validate the data
    // -----------------------------
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

    // -----------------------------
    // STEP 3: Escape strings before they touch SQL
    // -----------------------------
    // mysqli_real_escape_string() prevents SQL injection on string fields.
    // Numbers (cgpa) don't need escaping, just validation (done above).
    if (count($errors) === 0) {
        $safeName    = mysqli_real_escape_string($conn, $name);
        $safeEmail   = mysqli_real_escape_string($conn, $email);
        $safeBranch  = mysqli_real_escape_string($conn, $branch);
        $safeCourse  = mysqli_real_escape_string($conn, $course);
        $safeAddress = mysqli_real_escape_string($conn, $address);
        $safePhoto   = mysqli_real_escape_string($conn, $photo ?? '');
        $cgpaValue   = (float) $cgpa;

        // -----------------------------
        // STEP 4: Prevent duplicate email registrations
        // -----------------------------
        $checkSql = "SELECT id FROM students WHERE email = '$safeEmail'";
        $checkResult = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($checkResult) > 0) {
            $errors[] = "This email is already registered. Please use a different one.";
        } else {

            // -----------------------------
            // STEP 5: Insert the new student
            // -----------------------------
            $sql = "INSERT INTO students (name, email, branch, cgpa, course, address, photo)
                    VALUES ('$safeName', '$safeEmail', '$safeBranch', '$cgpaValue',
                            '$safeCourse', '$safeAddress', '$safePhoto')";

            if (mysqli_query($conn, $sql)) {
                $successMessage = "Student Registered Successfully!";
                $grade = calculateGrade($cgpaValue);

                // Bonus: tell the student what number registration they were
                $countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
                $countRow = mysqli_fetch_assoc($countResult);
                $studentCount = $countRow['total'];
            } else {
                $errors[] = "Database error: " . mysqli_error($conn);
            }
        }
    }
}

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

      <?php elseif ($successMessage !== ""): ?>

        <!-- ================= SUCCESS STATE ================= -->
        <div class="alert alert-success text-center">
          <h4 class="alert-heading mb-2">✅ <?php echo $successMessage; ?></h4>
          <p class="mb-0"><?php echo htmlspecialchars($name); ?>, you're now saved permanently in MySQL.</p>
        </div>

        <div class="alert <?php echo $grade['class']; ?> text-center fw-bold">
          Performance: <?php echo $grade['label']; ?> (CGPA <?php echo $cgpaValue; ?>)
        </div>

        <div class="alert alert-info text-center">
          You are student #<?php echo $studentCount; ?> in our system!
        </div>

        <div class="d-flex gap-2">
          <a href="index.html" class="btn btn-primary w-50">Register Another</a>
          <a href="students.php" class="btn btn-outline-primary w-50">View All Records</a>
        </div>

      <?php else: ?>

        <div class="alert alert-warning">
          No form data received. <a href="index.html">Go back to the registration form</a>.
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>
