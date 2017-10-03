<?php
    session_start();
    include 'dbh.php';

    $caption = mysqli_real_escape_string($conn, $_POST['cap']);
    $date = date("Y-m-d H:i:s", time());

    $pic = $_FILES['pic'];
    $fileName = $_FILES['pic']['name'];
    $fileTmpName = $_FILES['pic']['tmp_name'];
    $fileSize = $_FILES['pic']['size'];
    $fileError = $_FILES['pic']['error'];
    $fileType = $_FILES['pic']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (!isset($_SESSION['uid'])) {
        echo "<span class='error'>You're not connected. Please log in.</span>";
    }
    else {
        $uid = $_SESSION['uid'];
        
        if (!isset($_POST['submit'])) {
            echo 'There was an error';
        }
        else {
            if (empty($pic)) {
                echo "<span class='error'>Upload Picture.</span>";
            }
            else {
                if(in_array($fileActualExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 1000000) {
                            $fileNewName = $uid.uniqid('', true).".".$fileActualExt;
                            $fileDestination = 'uploads/p'.$fileNewName;
                            move_uploaded_file($fileTmpName, "../".$fileDestination);
                            $sql = "INSERT INTO posts (uid, caption, pic, p_date) VALUES ('$uid', '$caption', '$fileDestination', '$date');";
                            if (mysqli_query($conn, $sql)) {
                                echo "<span class='success'>Posted!</span>";
                                updateNumPosts($uid, $conn);
                                header("Location: ../index.php");
                            }
                            else {
                                echo "<span class='error'>There was an error. Try later!</span>";
                            }
                        }
                        else {
                            echo 'Your file is too big!';
                        }
                    }
                    else {
                        echo 'There was an error uploading your file!';
                    }
                }
                else {
                    echo 'You cannot upload files of this type!';
                }
                
            }
        }
    }

    function updateNumPosts($uid, $conn) {
        $sql = "SELECT posts FROM profile WHERE uid='$uid'";                                
        $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $posts = $result['posts'] + 1;
        mysqli_query($conn, "UPDATE profile SET posts = '$posts' WHERE uid ='$uid';");
    }
?>