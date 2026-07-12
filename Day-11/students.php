<?php
// Module 4: session guard — must run before anything else on this page
include 'auth_check.php';
include 'db_connect.php';
include 'functions.php';

$page = "records";

// -----------------------------
// Read the search term from the URL (?q=...), if any
// -----------------------------
$searchTerm = trim($_GET['q'] ?? '');

// -----------------------------
// Build the query. Prepared statements keep the LIKE search safe
// even though the term comes straight from user input.
// -----------------------------
if ($searchTerm !== '') {
    $sql = "SELECT * FROM students
            WHERE name LIKE ? OR branch LIKE ? OR email LIKE ?
            ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    $wildcard = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, "sss", $wildcard, $wildcard, $wildcard);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
}

$totalStudents = mysqli_num_rows($result);

// -----------------------------
// Total count for the badge — always the full table, not just search results
// -----------------------------
$allCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$allCount = mysqli_fetch_assoc($allCountResult)['total'];

// -----------------------------
// Read any status message passed in from edit.php / delete.php
// -----------------------------
$msg = $_GET['msg'] ?? '';
$msgName = $_GET['name'] ?? '';
$msgText = $_GET['text'] ?? '';

/**
 * Wraps a search term match in <mark> so it's highlighted yellow,
 * like a search engine result. Falls back to the escaped original
 * text when there's no active search.
 */
function highlight($text, $term) {
    $safeText = htmlspecialchars($text);
    if ($term === '') {
        return $safeText;
    }
    return preg_replace(
        '/(' . preg_quote($term, '/') . ')/i',
        '<mark>$1</mark>',
        $safeText
    );
}

include 'header.php';
?>

<div class="container pb-5">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">📋 Student Records</h3>
    <span class="badge bg-primary fs-6">Total Students: <?php echo $allCount; ?></span>
  </div>

  <!-- ================= STATUS ALERTS ================= -->
  <?php if ($msg === 'updated'): ?>
    <div class="alert alert-success alert-dismissible fade show" id="statusAlert">
      ✅ Record updated successfully<?php echo $msgName ? " for <strong>" . htmlspecialchars($msgName) . "</strong>" : ""; ?>.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif ($msg === 'deleted'): ?>
    <div class="alert alert-danger alert-dismissible fade show" id="statusAlert">
      🗑️ Record deleted successfully<?php echo $msgName ? " for <strong>" . htmlspecialchars($msgName) . "</strong>" : ""; ?>.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif ($msg === 'error'): ?>
    <div class="alert alert-warning alert-dismissible fade show" id="statusAlert">
      ⚠️ <?php echo htmlspecialchars($msgText ?: "Something went wrong."); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- ================= SEARCH BAR ================= -->
  <form action="students.php" method="get" class="mb-4">
    <div class="input-group">
      <span class="input-group-text">🔍</span>
      <input
        type="text"
        name="q"
        class="form-control"
        placeholder="Search by name, branch, or email..."
        value="<?php echo htmlspecialchars($searchTerm); ?>"
      />
      <button type="submit" class="btn btn-primary">Search</button>
      <?php if ($searchTerm !== ''): ?>
        <a href="students.php" class="btn btn-outline-secondary">Clear</a>
      <?php endif; ?>
    </div>
  </form>

  <!-- ================= RESULTS ================= -->
  <?php if ($totalStudents === 0): ?>

    <div class="alert alert-secondary text-center py-4">
      <?php if ($searchTerm !== ''): ?>
        No students found matching "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>".
        <br /><a href="students.php">Clear search</a>
      <?php else: ?>
        No students registered yet. <a href="index.html">Be the first to register!</a>
      <?php endif; ?>
    </div>

  <?php else: ?>

    <div class="table-responsive">
      <table class="table table-bordered table-hover table-striped align-middle bg-white">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Branch</th>
            <th>Course</th>
            <th>CGPA</th>
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
              $grade = calculateGrade((float) $row['cgpa']);
              $rowClass = ($row['cgpa'] > 8.0) ? 'table-success' : '';
              $lastUpdated = $row['updated_at'] ? $row['updated_at'] : '—';
            ?>
            <tr class="<?php echo $rowClass; ?>">
              <td><?php echo $row['id']; ?></td>
              <td><?php echo highlight($row['name'], $searchTerm); ?></td>
              <td><?php echo highlight($row['email'], $searchTerm); ?></td>
              <td><?php echo highlight($row['branch'], $searchTerm); ?></td>
              <td><?php echo htmlspecialchars($row['course'] ?? '—'); ?></td>
              <td><?php echo $row['cgpa']; ?></td>
              <td><small class="text-muted"><?php echo $lastUpdated; ?></small></td>
              <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-sm btn-outline-primary" title="Edit">
                   ✏️ Edit
                </a>
                <a href="#"
                   class="btn btn-sm btn-outline-danger delete-btn"
                   data-id="<?php echo $row['id']; ?>"
                   data-name="<?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>"
                   title="Delete">
                   🗑️ Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <p class="text-muted">
      Showing <?php echo $totalStudents; ?> student<?php echo $totalStudents === 1 ? '' : 's'; ?><?php echo $searchTerm !== '' ? ' matching your search' : ''; ?>.
    </p>

  <?php endif; ?>

  <a href="index.html" class="btn btn-primary">+ Register New Student</a>
</div>

<script>
  // Confirm before deleting — the golden rule from Module 2.
  // We intercept every .delete-btn click, ask window.confirm(),
  // and only navigate to delete.php if the user clicks OK.
  document.querySelectorAll('.delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const id = this.dataset.id;
      const name = this.dataset.name;
      const confirmed = window.confirm(
        `Are you sure you want to delete ${name}'s record? This action cannot be undone.`
      );
      if (confirmed) {
        window.location.href = 'delete.php?id=' + id;
      }
    });
  });

  // Auto-dismiss the status alert (success/danger/warning) after 3 seconds
  const statusAlert = document.getElementById('statusAlert');
  if (statusAlert) {
    setTimeout(function () {
      // Use Bootstrap's own Alert API if it's loaded, otherwise just hide it
      if (window.bootstrap && bootstrap.Alert) {
        const alertInstance = bootstrap.Alert.getOrCreateInstance(statusAlert);
        alertInstance.close();
      } else {
        statusAlert.style.display = 'none';
      }
    }, 3000);
  }
</script>
