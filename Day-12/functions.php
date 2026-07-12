<?php
/**
 * functions.php
 * Small reusable helpers shared across the PHP pages.
 */

/**
 * Works out a grade label + a matching Bootstrap alert color
 * based on the student's CGPA.
 */
function calculateGrade($cgpa) {
    if ($cgpa >= 9) {
        return ["label" => "Excellent", "class" => "alert-success", "row" => "table-success"];
    } elseif ($cgpa >= 7.5) {
        return ["label" => "Very Good", "class" => "alert-primary", "row" => ""];
    } elseif ($cgpa >= 6) {
        return ["label" => "Good", "class" => "alert-warning", "row" => ""];
    } else {
        return ["label" => "Keep Improving", "class" => "alert-danger", "row" => ""];
    }
}

/**
 * Day 12, Module 1.
 * Returns the right <img src="..."> path for a student's photo.
 *
 * The `photo` column has existed since Day 9, but it was only ever filled
 * with the literal string "placeholder.png" (see process_form.php's old
 * note about uploads being UI-only). That file doesn't actually exist in
 * uploads/, so we can't just trust a non-empty column anymore — we check
 * the real file is on disk before pointing to it, and fall back to the
 * default avatar in assets/ otherwise.
 */
function getStudentPhoto($photo) {
    if ($photo && $photo !== 'placeholder.png' && file_exists(__DIR__ . '/uploads/' . $photo)) {
        return 'uploads/' . $photo;
    }
    return 'assets/default.png';
}
?>
