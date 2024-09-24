<?php
include '..\components\dbconnect.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $searchQuery = "SELECT * FROM `item` WHERE `item_name` LIKE '%$query%' OR `item_id` LIKE '%$query%' ORDER BY item_id ASC";
    $result = mysqli_query($conn, $searchQuery);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="card text mb-3 rounded-3" style="max-width: 100%; background-color: #EC6509;">
                <a href="editItem.php?id=' . $row['item_id'] . '" class="text-decoration-none text-dark">
                    <div class="card-body">
                        <p class="card-title fw-bold">' . $row['item_name'] . ' <span class="ms-4">' . $row['unit'] . ' gm</span></p>
                        <p class="card-text fw-semibold">' . $row['item_id'] . ' <span style="float: inline-end;">' . $row['price'] . ' Tk</span></p>
                    </div>
                </a>
            </div>
            ';
        }
    } else {
        echo '<p class="text-danger">No items found.</p>';
    }
}
?>
