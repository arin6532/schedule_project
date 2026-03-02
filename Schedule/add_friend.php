<?php include('menu_Navbar_user.php'); ?>
<!DOCTYPE html>
<html>
    <style>
        .font-thai{
            font-family: 'Kanit', sans-serif;
        }
    </style>
<head>
<title>Friend</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- ลิงก์ CSS ของ Font Awesome -->
</head>
<body>
<div style="height: 150px;"></div>
    <div class="card p-3 container font-thai">
        <h2 class="text-center mt-5">ค้นหาเพื่อน</h2>
        <table class="table mt-4">
            <form class="form-group" action="" method="POST">
                <tbody>
                    <tr>
                        <td style="width: 100%;">
                            <input type="text" name="search_userid" placeholder="Search ID" class="form-control" required>
                        </td>
                        <td>
                            <input type="submit" value="ค้นหา" class="btn btn-warning">
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
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
            <?php
                require('dbconnect.php');
                // Include your database connection setup here
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $searchID = $_POST["search_userid"];

                    // Retrieve the User ID of the currently logged-in user from the session
                    $loggedInUserID = $_SESSION["user_id"];

                    // Check if the searched User ID is the same as the logged-in user
                    if ($searchID != $loggedInUserID) {
                        $sql = "SELECT * FROM userlogin WHERE user_id = '$searchID'";
                        $result = mysqli_query($con, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row["user_id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td><a href='status_add_friend.php?user_id=" . $row["user_id"] . "'><i class='fas fa-user-plus'></i></a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "Error: " . mysqli_error($con);
                        }
                    }
                }
                mysqli_close($con);
            ?>
            </tbody>
        </table>

        <center><a href="indexuser.php" class="btn btn-primary mt-5">กลับหน้าหลัก</a>
                <a href="friend_user.php" class="btn btn-outline-primary mt-5">ย้อนกลับ</a><br><br></center>
    </div>
</body>
</html>