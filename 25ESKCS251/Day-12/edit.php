<?php
// Module 4: session guard — must run before anything else on this page
include 'auth_check.php';
include 'db_connect.php';
include 'functions.php';

$page = "records"; // keeps the "Student Records" nav link highlighted

$errors = [];
$student = null;

// -----------------------------
// STEP 1: Which student are we editing?
// -----------------------------
$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: students.php?msg=error&text=" . urlencode("No valid student ID provided."));
    exit;
}
$id = (int) $id;

// -----------------------------
// STEP 2: Handle the form submission (UPDATE)
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $branch  = trim($_POST['branch'] ?? '');
    $cgpa    = $_POST['cgpa'] ?? '';
    $course  = trim($_POST['course'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Day 12, Module 1: keep the existing photo unless a new one is uploaded.
    $photo = $_POST['existing_photo'] !== '' ? $_POST['existing_photo'] : null;

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
            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('student_') . '.' . $ext;

            if (move_uploaded_file($file['tmp_name'], 'uploads/' . $filename)) {
                $photo = $filename;
            } else {
                $errors[] = "Could not save the uploaded photo.";
            }
        }
    }

    // Same validation rules as the registration form
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

    // Make sure no OTHER student already owns this email
    if (count($errors) === 0) {
        $dupCheck = mysqli_prepare($conn, "SELECT id FROM students WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($dupCheck, "si", $email, $id);
        mysqli_stmt_execute($dupCheck);
        $dupResult = mysqli_stmt_get_result($dupCheck);

        if (mysqli_num_rows($dupResult) > 0) {
            $errors[] = "Another student is already using this email.";
        }
    }

    if (count($errors) === 0) {
        // -----------------------------
        // STEP 3: Run the UPDATE using a prepared statement
        // -----------------------------
        // Prepared statements separate the SQL structure from the data,
        // which is the safest way to prevent SQL injection — safer than
        // mysqli_real_escape_string() alone.
        $sql = "UPDATE students
                SET name = ?, email = ?, branch = ?, cgpa = ?, course = ?, address = ?, photo = ?
                WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        // Types: s = string, d = decimal/double, i = integer
        mysqli_stmt_bind_param(
            $stmt,
            "sssdsssi",
            $name, $email, $branch, $cgpa, $course, $address, $photo, $id
        );

        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the table with a success message (Module 1 challenge)
            header("Location: students.php?msg=updated&name=" . urlencode($name));
            exit;
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }

    // If we reach here, validation failed — keep the submitted values on screen
    $student = [
        'id' => $id, 'name' => $name, 'email' => $email,
        'branch' => $branch, 'cgpa' => $cgpa,
        'course' => $course, 'address' => $address,
        'photo' => $photo
    ];
}

// -----------------------------
// STEP 4: First page load (GET) — fetch the current record to pre-fill the form
// -----------------------------
if ($student === null) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    if (!$student) {
        header("Location: students.php?msg=error&text=" . urlencode("Student not found."));
        exit;
    }
}

include 'header.php';
?>

<div class="container pb-5">
  <div class="row justify-content-center">
    <div class="col-md-7">

      <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger">
          <h5 class="alert-heading">⚠️ Please fix the following:</h5>
          <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h3 class="mb-0">✏️ Edit Student</h3>
        </div>

        <div class="card-body">
          <!-- Same form as index.html, but every field's value is pre-filled -->
          <form action="edit.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo (int) $student['id']; ?>" />
            <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($student['photo'] ?? ''); ?>" />

            <!-- Day 12, Module 1: replace photo (optional) -->
            <div class="mb-3 d-flex align-items-center gap-3">
              <img id="photoPreview" src="<?php echo htmlspecialchars(getStudentPhoto($student['photo'] ?? null)); ?>"
                   alt="Current photo" class="student-avatar" style="width:70px;height:70px;" />
              <div class="flex-grow-1">
                <label for="photo" class="form-label">Replace Photo (optional)</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/gif" />
                <div class="form-text">Leave empty to keep the current photo.</div>
              </div>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="name" name="name"
                     value="<?php echo htmlspecialchars($student['name']); ?>" required />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" name="email"
                     value="<?php echo htmlspecialchars($student['email']); ?>" required />
            </div>

            <div class="mb-3">
              <label for="cgpa" class="form-label">CGPA</label>
              <input type="number" step="0.01" min="0" max="10" class="form-control"
                     id="cgpa" name="cgpa"
                     value="<?php echo htmlspecialchars($student['cgpa']); ?>" required />
            </div>

            <div class="mb-3">
              <label for="branch" class="form-label">Branch</label>
              <select class="form-select" id="branch" name="branch" required>
                <?php
                  $branches = ["Computer Science", "Information Technology", "Electronics", "Mechanical", "Civil"];
                  foreach ($branches as $b) {
                      $selected = ($student['branch'] === $b) ? 'selected' : '';
                      echo "<option value=\"$b\" $selected>$b</option>";
                  }
                ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="course" class="form-label">Course</label>
              <input type="text" class="form-control" id="course" name="course"
                     value="<?php echo htmlspecialchars($student['course'] ?? ''); ?>" />
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="2"
              ><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary w-50">Save Changes</button>
              <a href="students.php" class="btn btn-outline-secondary w-50">Cancel</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // Day 12, Module 1 Super Bonus: preview the newly chosen photo before submit.
  document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => document.getElementById('photoPreview').src = e.target.result;
    reader.readAsDataURL(file);
  });
</script>
