<?php include('menu_Navbar_user.php'); ?>

<?php
// Include your database connection setup
require('dbconnect.php');

// Get the user ID of the currently logged-in user
$userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

// Initialize a variable to store the search query
$searchQuery = "";

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_userid"])) {
    $searchQuery = $_POST["search_userid"];
}

// Retrieve friend_user data along with userlogin data based on id_sender being the current user and id_recipient being the specific user
$query = "SELECT f.id_sender, u.name as friend_name, u.email as friend_email, u.imgg as friend_imgg, f.friend_type
          FROM friend_user f
          JOIN userlogin u ON f.id_sender = u.user_id
          WHERE f.id_recipient = '$userId' AND f.id_sender IN (SELECT user_id FROM userlogin WHERE id_recipient = '$userId')
          AND (u.user_id LIKE '%$searchQuery%' OR u.name LIKE '%$searchQuery%')";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Friend List</title>
    <style>
        /* Add CSS styles for circular images */
        .circle-image {
            border-radius: 50%; /* Make the image circular */
            width: 50px; /* Set the width */
            height: 50px; /* Set the height */
        }
        .font-thai{
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body>
    <div style="height: 150px;"></div>
    <div class="card p-3 container font-thai">
        <h2 class="text-center mt-5">คำขอเป็นเพื่อน</h2>
        <table class="table mt-4">
            <form class="form-group" action="" method="POST">
                <tbody>
                    <tr>
                        <td style="width: 100%;">
                            <input type="text" name="search_userid" placeholder="Search ID or Name" class="form-control" required>
                        </td>
                        <td>
                            <input type="submit" value="Search" class="btn btn-warning">
                        </td>
                    </tr>
                </tbody>
            </form>
        </table>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>Email</th>
                    <th>ภาพโปรไฟล์</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $friendId = $row["id_sender"]; // The friend's ID is the sender's ID
                    $friendType = $row["friend_type"];

                    // Check if it's a friend request (friend_type = 1)
                    if ($friendType == 1) {
                        echo "<tr>";
                        echo "<td>" . $friendId . "</td>";
                        echo "<td>" . $row["friend_name"] . "</td>";
                        echo "<td>" . $row["friend_email"] . "</td>";
                        echo "<td><img class='circle-image' src='" . $row["friend_imgg"] . "' width='50' height='50'></td>";
                        echo "<td>";
                        echo "<a href='accept_friend.php?user_id=$friendId' class='btn btn-success btn-sm'>✓</a>&nbsp;";
                        echo "<a href='reject_friend.php?user_id=$friendId' class='btn btn-danger btn-sm'>✕</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <center>
            <a href="indexuser.php" class="btn btn-primary mt-5">หน้าแรก</a>
            <a href="friend_user.php" class="btn btn-outline-primary mt-5">ย้อนกลับ</a><br><br>
        </center>
    </div>
</body>
</html>

