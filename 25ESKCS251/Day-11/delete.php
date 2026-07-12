<?php
// Module 4: session guard — must run before anything else on this page
include 'auth_check.php';
include 'db_connect.php';

// -----------------------------
// STEP 1: Which student are we deleting?
// -----------------------------
// The confirm() dialog runs in JavaScript BEFORE this page is ever
// requested (see the Delete button in students.php) — by the time
// this script runs, the user has already clicked "OK".
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: students.php?msg=error&text=" . urlencode("No valid student ID provided."));
    exit;
}
$id = (int) $id;

// -----------------------------
// STEP 2: Look up the name first, so the success message can be
// personalized ("Rahul's record was deleted" instead of just "Deleted").
// -----------------------------
$lookup = mysqli_prepare($conn, "SELECT name FROM students WHERE id = ?");
mysqli_stmt_bind_param($lookup, "i", $id);
mysqli_stmt_execute($lookup);
$result = mysqli_stmt_get_result($lookup);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: students.php?msg=error&text=" . urlencode("Student not found — may already be deleted."));
    exit;
}

// -----------------------------
// STEP 3: Run the DELETE using a prepared statement
// -----------------------------
// WHERE id = ? is the golden rule here — without it, this would
// wipe every row in the table.
$stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: students.php?msg=deleted&name=" . urlencode($student['name']));
    exit;
} else {
    header("Location: students.php?msg=error&text=" . urlencode("Database error: " . mysqli_error($conn)));
    exit;
}
?>
