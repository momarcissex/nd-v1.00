<?php 
    include 'dbh.php';
    session_start();

    if (isset($_SESSION['uid'])) {
        if (isset($_POST['change-pwd'])) {
            $opwd = md5(mysqli_real_escape_string($conn, $_POST['opwd']));
            $pwd = $_SESSION['pwd'];
            if ($opwd != $pwd) {
                echo "<span class='error'>Current Password does not match!</span>";
            }
            else {
                $npwd = md5(mysqli_real_escape_string($conn, $_POST['npwd']));
                $cpwd = md5(mysqli_real_escape_string($conn, $_POST['cpwd']));

                if ($npwd != $cpwd) {
                    echo "<span class='error'>Enter same password.</span>";
                }
                else {
                    updatePassword($conn, $npwd, $_SESSION['email']);
                }
            }
        }

        if (isset($_POST['submit'])) {
            $fname = mysqli_real_escape_string($conn, $_POST['first_name']);
            $lname = mysqli_real_escape_string($conn, $_POST['last_name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $bio = mysqli_real_escape_string($conn, $_POST['bio']);

            if (empty($fname) || empty($lname) || empty($email) || empty($username)) {
                echo "<span class='error'>Field all required fields!</span>";
            }
            else {
                updateRecords($conn, $fname, $lname, $email, $username, $bio);
                $uid = $_SESSION['uid'];
                $sql = "SELECT * FROM users WHERE uid='$uid';";
                $result = $conn->query($sql);
                $check = mysqli_num_rows($result);
                $row = mysqli_fetch_assoc($result);
                $_SESSION['uid'] = $row['uid'];
                $_SESSION['fname'] = $row['first_name'];
                $_SESSION['lname'] = $row['last_name'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['pwd'] = $row['pwd'];
            }
        }
    }
    else {
        echo "<span class='error'>You're not connected!</span>";
    }

    function updatePassword($conn, $pwd, $email) {
        $sql = "UPDATE users SET pwd = '$pwd' WHERE email = '$email';";
        mysqli_query($conn, $sql);
        echo "<span class='success'>Password changed.</span>";
    }

    function updateRecords($conn, $fname, $lname, $email, $username, $bio) {
        $uid = $_SESSION['uid'];
        $sql = "UPDATE users SET first_name = '$fname', last_name = '$lname', email = '$email', username = '$username' WHERE uid = '$uid';";
        $sql2 = "UPDATE profile SET bio = '$bio' WHERE uid = '$uid';";
        mysqli_query($conn, $sql);
        mysqli_query($conn, $sql2);
        echo "<span class='success'>Changes saved!</span>";
    }
?>
<!DOCTYPE html>

<html>
    <title>
        NXTDROP: The Social Marketplace
    </title>
    <head>
        <link type="text/css" rel="stylesheet" href="edit-profile.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    </head>

    <body>
        <header>
            <a href="index.php"><img id ="logo"src="img/nxtdroplogo.png" width="125px"></a>
        </header>
        
        <div class="container">
            <form action="" method="POST" class="login-form">
                <input type="text" name="first_name" value="<?php if (isset($_SESSION['uid'])) echo $_SESSION['fname'];?>" required></br>
                <input type="text" name="last_name" value="<?php if (isset($_SESSION['uid'])) echo $_SESSION['lname'];?>" required></br>
                <input type="text" name="email" value="<?php if (isset($_SESSION['uid'])) echo $_SESSION['email'];?>"required></br>
                <input type="text" name="username" value="<?php if (isset($_SESSION['uid'])) echo $_SESSION['username'];?>"required></br>
                <textarea name="bio" placeholder="Bio"></textarea></br>
                <button type="submit" name="submit" id="submit">Save Changes</button>
            </form>
            </br></br>
            <form action="" method="POST" class="change-pwd-form">
                <input type="password" name="opwd" placeholder="Enter Old Password" required></br>
                <input type="password" name="npwd" placeholder="Enter New Password" required></br>
                <input type="password" name="cpwd" placeholder="Confirm Password" required></br>
                <button type="submit" name="change-pwd" id="change-pwd">Change Password</button>
            </form>
            </br></br>
            <a href="profile.php"><p>Back to Profile</p></a>
        </div>
    </body>
</html>