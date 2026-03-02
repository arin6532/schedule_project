<?php include('component_user.php'); 
session_start();
?>
<?php
// Start session to retrieve user ID

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
    $friendUserId = $_GET["user_id"];

    // Include your database connection setup
    require('dbconnect.php');

    // Get the user ID of the currently logged-in user
    $senderId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

    // Check if a friendship already exists with the same sender and recipient IDs
    $checkQuery = "SELECT * FROM friend_user WHERE (id_sender = '$senderId' AND id_recipient = '$friendUserId') OR (id_sender = '$friendUserId' AND id_recipient = '$senderId')";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Friendship or friend request already exists
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "Information Update",
                    text: "เป็นเพื่อนกันแล้ว หรือมีการส่งคำขอมาแล้ว",
                    type: "info"
                }, function() {
                    window.location.href="add_friend.php";
                });
            }, 1000);
        </script>';
    } else {
        // Friendship doesn't exist, proceed to add friend
        // Retrieve user data from userlogin table based on user_id
        $query = "SELECT * FROM userlogin WHERE user_id = '$friendUserId'";
        $result = mysqli_query($con, $query);

        if ($result) {
            $userData = mysqli_fetch_assoc($result);

            $friendId = $userData["user_id"];
            $friendName = $userData["name"];
            $friendEmail = $userData["email"];
            $friendImgg = $userData["imgg"];

            // Insert friend data into friend_user table with friend_type of 1
            $insertQuery = "INSERT INTO friend_user (id_sender, id_recipient, friend_name, friend_email, friend_imgg, friend_type) VALUES ('$senderId', '$friendId', '$friendName', '$friendEmail', '$friendImgg', 1)";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "Information Update",
                            text: "ส่งคำขอเป็นเพื่อนสำเร็จ",
                            type: "success"
                        }, function() {
                            window.location.href="add_friend.php";
                        });
                    }, 1000);
                </script>';
            } else {
                // Error inserting friend data
                echo "Error: " . mysqli_error($con);
            }
        } else {
            // Error retrieving user data
            echo "Error: " . mysqli_error($con);
        }
    }

    mysqli_close($con);
}
?>
