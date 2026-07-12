<?php
include 'db_connect.php';
include 'functions.php';

$page = "records"; // used by header.php to highlight the "Student Records" nav link

include 'header.php';
?>

<div class="container pb-5">
  <h3 class="mb-4">📋 Student Records</h3>

  <?php
    // SELECT * fetches every column from every row in the students table.
    $sql = "SELECT * FROM students ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $totalStudents = mysqli_num_rows($result);
  ?>

  <?php if ($totalStudents === 0): ?>

    <div class="alert alert-secondary">
      No students registered yet.
      <a href="index.html">Be the first to register!</a>
    </div>

  <?php else: ?>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle bg-white">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Branch</th>
            <th>Course</th>
            <th>CGPA</th>
            <th>Registered On</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // mysqli_fetch_assoc() returns one row at a time as an
            // associative array, and returns false once rows run out —
            // which is exactly what ends this while loop.
            while ($row = mysqli_fetch_assoc($result)) {
                $grade = calculateGrade((float) $row['cgpa']);

                // Highlight rows with CGPA > 8.0 in green (Mission 4 challenge)
                $rowClass = ($row['cgpa'] > 8.0) ? 'table-success' : '';

                echo "<tr class='$rowClass'>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['branch']) . "</td>";
                echo "<td>" . htmlspecialchars($row['course'] ?? '—') . "</td>";
                echo "<td>" . $row['cgpa'] . "</td>";
                echo "<td>" . $row['date_registered'] . "</td>";
                echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Bonus: total record counter using mysqli_num_rows() -->
    <div class="alert alert-info">
      Total Students: <strong><?php echo $totalStudents; ?></strong>
    </div>

  <?php endif; ?>

  <a href="index.html" class="btn btn-primary">+ Register New Student</a>
</div>

