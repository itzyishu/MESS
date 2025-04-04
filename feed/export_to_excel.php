<?php
// Include database connection
include 'db_connect.php';

// Get the report type from the URL parameter
$report_type = isset($_GET['type']) ? $_GET['type'] : 'all';
$specific_id = isset($_GET['id']) ? $_GET['id'] : null;

// Prepare filename based on report type
$filename = "mess_feedback_" . $report_type . "_" . date("Y-m-d") . ".xls";

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Create appropriate query based on report type
$query = "";

switch ($report_type) {
    case 'student':
        // Student-specific report (if ID is provided)
        if ($specific_id) {
            $student_id = mysqli_real_escape_string($conn, $specific_id);
            $query = "SELECT * FROM mess_feedback WHERE registration_number = '$student_id' ORDER BY submission_date DESC";
        } else {
            // Group by student with count and latest submission
            $query = "SELECT registration_number, name, COUNT(*) as feedback_count, 
                     MAX(submission_date) as latest_feedback, mess_type 
                     FROM mess_feedback GROUP BY registration_number 
                     ORDER BY feedback_count DESC";
        }
        break;
        
    case 'monthly':
        // Monthly report - group by month
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $query = "SELECT * FROM mess_feedback 
                 WHERE MONTH(submission_date) = '$month' AND YEAR(submission_date) = '$year' 
                 ORDER BY submission_date DESC";
        break;
        
    case 'weekly':
        // Weekly report - get data from the past 7 days
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-7 days'));
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $query = "SELECT * FROM mess_feedback 
                 WHERE DATE(submission_date) BETWEEN '$start_date' AND '$end_date' 
                 ORDER BY submission_date DESC";
        break;
        
    case 'meal':
        // Meal-wise report
        $meal_type = isset($_GET['meal']) ? $_GET['meal'] : 'breakfast';
        
        // Columns to select based on meal type
        $meal_columns = "";
        switch ($meal_type) {
            case 'breakfast':
                $meal_columns = "name, registration_number, mess_type, form_date, breakfast_suggestions";
                break;
            case 'lunch':
                $meal_columns = "name, registration_number, mess_type, form_date, lunch_suggestions";
                break;
            case 'snack':
                $meal_columns = "name, registration_number, mess_type, form_date, snack_suggestions";
                break;
            case 'dinner':
                $meal_columns = "name, registration_number, mess_type, form_date, dinner_suggestions";
                break;
        }
        
        $query = "SELECT id, $meal_columns, submission_date FROM mess_feedback 
                 WHERE {$meal_type}_suggestions != '' 
                 ORDER BY submission_date DESC";
        break;
        
    default:
        // Default to all records
        $query = "SELECT * FROM mess_feedback ORDER BY submission_date DESC";
}

$result = mysqli_query($conn, $query);

// Check if there is data
if (mysqli_num_rows($result) > 0) {
    // Output Excel file headers
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <title>Mess Feedback Report - $report_type</title>
    </head>
    <body>
        <table border='1'>
            <thead>
                <tr>";
    
    // Get column names based on first row
    $first_row = mysqli_fetch_assoc($result);
    foreach ($first_row as $column => $value) {
        echo "<th>" . ucwords(str_replace('_', ' ', $column)) . "</th>";
    }
    
    echo "</tr></thead><tbody>";
    
    // Output first row
    echo "<tr>";
    foreach ($first_row as $value) {
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
    
    // Output remaining rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</tbody></table></body></html>";
} else {
    echo "<h3>No data found for this report type</h3>";
}

// Close connection
mysqli_close($conn);
?>