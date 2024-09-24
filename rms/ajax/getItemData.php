<?php
// Include the database connection
include '../components/dbconnect.php';

// Retrieve the search parameter and item ID, if provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$itemId = isset($_GET['id']) ? intval($_GET['id']) : 0; // Ensure it's an integer

// Prepare the base query
$query = "SELECT item_id, item_name, unit, price FROM item";

// Modify the query if a search term is provided
if (!empty($searchTerm)) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $query .= " WHERE item_name LIKE '%$searchTerm%'";
}

// If an item ID is provided, modify the query to return that specific item
if ($itemId > 0) {
    $query = "SELECT item_id, item_name, unit, price FROM item WHERE item_id = $itemId";
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
