<?php
/**
 * functions.php
 * All reusable PHP logic for the Registration Confirmation System
 * lives here, so process.php stays clean and readable.
 */

/**
 * Works out a grade label + a matching Bootstrap alert color
 * based on the student's CGPA.
 *
 * Returns an associative array like:
 * [ "label" => "Excellent", "class" => "alert-success" ]
 */
function calculateGrade($cgpa) {
    if ($cgpa >= 9) {
        return ["label" => "Excellent", "class" => "alert-success"];
    } elseif ($cgpa >= 7.5) {
        return ["label" => "Very Good", "class" => "alert-primary"];
    } elseif ($cgpa >= 6) {
        return ["label" => "Good", "class" => "alert-warning"];
    } else {
        return ["label" => "Keep Improving", "class" => "alert-danger"];
    }
}

/**
 * Returns today's date nicely formatted, e.g. "Sunday, July 12, 2026"
 */
function getFormattedDate() {
    return date("l, F j, Y");
}

/**
 * Returns a greeting that changes based on the current hour (24hr clock).
 */
function getGreeting() {
    $hour = (int) date("H");

    if ($hour < 12) {
        return "Good Morning";
    } elseif ($hour < 17) {
        return "Good Afternoon";
    } else {
        return "Good Evening";
    }
}
?>
