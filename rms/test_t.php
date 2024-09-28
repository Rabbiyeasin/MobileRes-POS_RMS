<?php
// Database connection settings

$dbhost = "localhost";
$dbname = "rms";
$dbuser = "root";
$dbpass = "";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the start and end dates from the form submission
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Generate daily sales report
    $daily_report = getDailySalesReport($conn, $start_date, $end_date);

    // Generate monthly sales report
    $monthly_report = getMonthlySalesReport($conn, $start_date, $end_date);
}

// Function to generate daily sales report
function getDailySalesReport($conn, $start_date, $end_date) {
    $daily_report = array();

    $query = "SELECT 
                    DATE(o.Order_Date) AS date, 
                    SUM(o.Total_Amount) AS total_sales
                FROM 
                    orders o
                WHERE 
                    o.Order_Date BETWEEN '$start_date' AND '$end_date'
                GROUP BY 
                    DATE(o.Order_Date)
                ORDER BY 
                    date ASC";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $daily_report[] = array(
                'date' => $row['date'],
                'total_sales' => $row['total_sales']
            );
        }
    }

    return $daily_report;
}

// Function to generate monthly sales report
function getMonthlySalesReport($conn, $start_date, $end_date) {
    $monthly_report = array();

    $query = "SELECT 
                    MONTH(o.Order_Date) AS month, 
                    YEAR(o.Order_Date) AS year, 
                    SUM(o.Total_Amount) AS total_sales
                FROM 
                    orders o
                WHERE 
                    o.Order_Date BETWEEN '$start_date' AND '$end_date'
                GROUP BY 
                    MONTH(o.Order_Date), YEAR(o.Order_Date)
                ORDER BY 
                    year ASC, month ASC";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $monthly_report[] = array(
                'month' => $row['month'],
                'year' => $row['year'],
                'total_sales' => $row['total_sales']
            );
        }
    }

    return $monthly_report;
}

// Close the database connection
$conn->close();
?>

<!-- HTML form to select start and end dates -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required><br><br>
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required><br><br>
    <input type="submit" value="Generate Report">
</form>

<!-- Display daily sales report -->
<h2>Daily Sales Report</h2>
<table border="1">
    <tr>
        <th>Date</th>
        <th>Total Sales</th>
    </tr>
    <?php if (isset($daily_report)) { ?>
        <?php foreach ($daily_report as $row) { ?>
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['total_sales']; ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
</table>

<!-- Display monthly sales report -->
<h2>Monthly Sales Report</h2>
<table border="1">
    <tr>
        <th>Month</th>
        <th>Year</th>
        <th>Total Sales</th>
    </tr>
    <?php if (isset($monthly_report)) { ?>
        <?php foreach ($monthly_report as $row) { ?>
            <tr>
                <td><?php echo $row['month']; ?></td>
                <td><?php echo $row['year']; ?></td>
                <td><?php echo $row['total_sales']; ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
</table>
``