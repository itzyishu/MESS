<?php
// Include database connection
require_once 'db_connect.php';

// Query to get the feedback data
$query = "SELECT * FROM mess_feedback ORDER BY submission_date DESC LIMIT 50";
$result = mysqli_query($conn, $query);

// Get the total number of records
$count_query = "SELECT COUNT(*) as total FROM mess_feedback";
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total_records = $total_row['total'];

// Get unique students count
$students_query = "SELECT COUNT(DISTINCT registration_number) as total_students FROM mess_feedback";
$students_result = mysqli_query($conn, $students_query);
$students_row = mysqli_fetch_assoc($students_result);
$total_students = $students_row['total_students'];

// Get current month count
$current_month = date('m');
$current_year = date('Y');
$month_query = "SELECT COUNT(*) as month_count FROM mess_feedback WHERE MONTH(submission_date) = '$current_month' AND YEAR(submission_date) = '$current_year'";
$month_result = mysqli_query($conn, $month_query);
$month_row = mysqli_fetch_assoc($month_result);
$month_count = $month_row['month_count'];

// Get current week count
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));
$week_query = "SELECT COUNT(*) as week_count FROM mess_feedback WHERE submission_date BETWEEN '$week_start' AND '$week_end'";
$week_result = mysqli_query($conn, $week_query);
$week_row = mysqli_fetch_assoc($week_result);
$week_count = $week_row['week_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mess Feedback Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .stat-box {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 15px;
            flex: 1;
            margin-right: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-box:last-child {
            margin-right: 0;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            margin: 5px 0;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .export-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .export-btn:hover {
            background-color: #45a049;
        }
        .reports-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .report-form {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }
        select, input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h1>Mess Feedback Dashboard</h1>
            <a href="export.php" class="export-btn">Export All Data</a>
        </div>
        
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-label">Total Feedback Records</div>
                <div class="stat-number"><?php echo $total_records; ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Students</div>
                <div class="stat-number"><?php echo $total_students; ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">This Month</div>
                <div class="stat-number"><?php echo $month_count; ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">This Week</div>
                <div class="stat-number"><?php echo $week_count; ?></div>
            </div>
        </div>
        
        <div class="reports-section">
            <h2>Generate Reports</h2>
            
            <!-- Student-wise Report -->
            <div class="report-form">
                <h3>Student-wise Report</h3>
                <form action="export_to_excel.php" method="get">
                    <input type="hidden" name="type" value="student">
                    <div class="form-group">
                        <label for="id">Student ID:</label>
                        <input type="text" id="id" name="id" placeholder="Leave empty for all students">
                    </div>
                    <button type="submit" class="export-btn">Generate Student Report</button>
                </form>
            </div>
            
            <!-- Monthly Report -->
            <div class="report-form">
                <h3>Monthly Report</h3>
                <form action="export_to_excel.php" method="get">
                    <input type="hidden" name="type" value="monthly">
                    <div class="form-group">
                        <label for="month">Month:</label>
                        <select id="month" name="month">
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $month_name = date('F', mktime(0, 0, 0, $i, 1));
                                $selected = ($i == date('m')) ? 'selected' : '';
                                echo "<option value='$i' $selected>$month_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Year:</label>
                        <select id="year" name="year">
                            <?php
                            $current_year = date('Y');
                            for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                $selected = ($i == $current_year) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="export-btn">Generate Monthly Report</button>
                </form>
            </div>
            
            <!-- Weekly Report -->
            <div class="report-form">
                <h3>Weekly/Date Range Report</h3>
                <form action="export_to_excel.php" method="get">
                    <input type="hidden" name="type" value="weekly">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <button type="submit" class="export-btn">Generate Weekly Report</button>
                </form>
            </div>
            
            <!-- Meal-wise Report -->
            <div class="report-form">
                <h3>Meal-wise Report</h3>
                <form action="export_to_excel.php" method="get">
                    <input type="hidden" name="type" value="meal">
                    <div class="form-group">
                        <label for="meal">Meal Type:</label>
                        <select id="meal" name="meal">
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="snack">Snack</option>
                            <option value="dinner">Dinner</option>
                        </select>
                    </div>
                    <button type="submit" class="export-btn">Generate Meal Report</button>
                </form>
            </div>
        </div>
        
        <h2>Recent Feedback Entries</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Registration No.</th>
                    <th>Mess Type</th>
                    <th>Form Date</th>
                    <th>Suggestion Days</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($total_records > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>" . $row['id'] . "</td>
                            <td>" . $row['name'] . "</td>
                            <td>" . $row['registration_number'] . "</td>
                            <td>" . $row['mess_type'] . "</td>
                            <td>" . $row['form_date'] . "</td>
                            <td>" . $row['suggestion_days'] . "</td>
                            <td><a href='export.php?type=student&id=" . $row['registration_number'] . "'>View Details</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>