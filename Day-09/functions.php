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
?>
