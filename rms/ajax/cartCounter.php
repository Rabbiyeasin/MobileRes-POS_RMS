<?php
session_start();
// Include the database connection
include '../components/dbconnect.php'; // Ensure this file defines the $conn mysqli object

// Assuming you're using /cart/count to return the count
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user_id = $_SESSION['id']; // Assuming user is logged in and user_id is stored in session

    // Query to count the number of items in the cart for the current user
    $query = "SELECT COUNT(*) AS count FROM cart WHERE user_ID = $user_id AND validity = '1'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['count' => $row['count']]);
    } else {
        // Handle query failure (optional)
        echo json_encode(['error' => 'Failed to retrieve cart count']);
    }

    // Close the connection (optional, as it's generally closed at the end of the script)
    // mysqli_close($conn);
}
?>
