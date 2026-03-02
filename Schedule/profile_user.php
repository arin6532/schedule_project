<?php include('menu_Navbar_user.php');?>
<style>
        .card-form{
            margin-top: 100px;
        }
        .font-thai{
            font-family: 'Kanit', sans-serif;
        }
    </style>
<!DOCTYPE html>
<html lang="en">
<body>
    <!-- สร้างการ์ดเพื่อคลุมฟอร์ม -->
    <div class="card-container">
        <div class="card-form">
            <!-- วางฟอร์ม -->
            <form class="row g-3" action="update_Profile_user.php" method="POST" enctype="multipart/form-data">
                <!-- วางรูปภาพ -->
                <center>
                    <!-- Display the image from the database or use default image -->
                    <img class="profile-image" src="<?php echo $row['imgg'] ? $row['imgg'] : 'assets/images/person.png'; ?>" alt="Profile Image">
                    <br><br>
                    <input type="file" name="imgg" accept="image/*" >
                    <br><br>
                </center>
                
                <div class="col-md-12 font-thai">
                    <label for="name" class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name'] ?>">
                </div>
                <div class="col-md-6 font-thai">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username'] ?>">
                </div>
                <div class="col-md-6 font-thai">
                    <label for="Password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="Password" name="pwd" value="<?php echo $row['pwd'] ?>">
                </div>
                <div class="col-8 font-thai">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email'] ?>">
                </div>
                <div class="col-4 font-thai">
                    <label for="id" class="form-label">Id</label>
                    <input type="text" class="form-control" id="id" value="<?php echo $row['user_id'] ?> "readonly>
                </div>
                <!-- <div class="col-md-6">
                    <label for="inputCity" class="form-label">City</label>
                    <input type="text" class="form-control" id="inputCity">
                </div>
                <div class="col-md-4">
                    <label for="inputState" class="form-label">State</label>
                    <select id="inputState" class="form-select">
                        <option selected>Choose...</option>
                        <option>...</option>
                    </select>
                </div> -->
                <!-- <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck">
                        <label class="form-check-label" for="gridCheck">
                            Check me out
                        </label>
                    </div>
                </div> -->
                <div class="col-12 font-thai">
                    <center><button type="submit" class="btn btn-primary">บันทึก</button></center>
                </div>
                
            </form>
        </div>
    </div>
    <div style="height: 200px;"></div>
</body>
</html>