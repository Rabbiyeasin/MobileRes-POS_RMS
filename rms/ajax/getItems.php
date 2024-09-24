<?php
// Include the database connection
include '../components/dbconnect.php';

// Retrieve the search parameter, if provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the base query
$query = "SELECT item_id, item_name, unit, price FROM item";

// Modify the query if a search term is provided
if (!empty($searchTerm)) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $query .= " WHERE item_name LIKE '%$searchTerm%'";
}

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $items = [];

    // Fetch all items and store them in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    // Return the result in JSON format
    header('Content-Type: application/json');
    echo json_encode($items);

} else {
    // If query fails, return an error
    header('Content-Type: application/json');
    echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
}

// Close the database connection
mysqli_close($conn);
?>
