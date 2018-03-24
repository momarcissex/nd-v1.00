<?php
    session_start();
    date_default_timezone_set("UTC"); 
    include '../dbh.php';
    $date = date("Y-m-d H:i:s", time());

    $to_from = $_POST['to'];
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username = '$to_from';"));
    $to = $r['uid'];
    $msg = mysqli_real_escape_string($conn, $_POST['msg']);
    $from = $_SESSION['uid'];

    $query = "SELECT username FROM users WHERE username = '$to_from';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_num_rows($result);

    if (!empty($_FILES['file']['name'])) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if(in_array($fileActualExt, $allowed)) {
            //if ($fileError === 0) {
                if ($fileSize < 10000000) {
                    $fileNewName = $to.$from.uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'uploads/p'.$fileNewName;
                    move_uploaded_file($fileTmpName, "../".$fileDestination);
                }
                else {
                    echo 'Your file is too big!';
                    die;
                }
            //}
            /*else {
                echo 'There was an error uploading your file!';
                die;
            }*/
        }
        else {
            echo 'You cannot upload files of this type!';
            die;
        }
    }
    else {
        $fileDestination = '';
    }

    if (!isset($_SESSION['uid'])) {
        header('Location: ../index.php');
    }
    else {
        if ($to == "") {
            echo 'Enter a name';
        }
        else {
            if ($row < 1) {
                echo "Username does not exist!";
            }
            else {
                if ($msg == "" && empty($_FILES['file']['name'])) {
                    echo 'Enter a message or send a picture!';
                }
                else {
                    $r = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM messages WHERE u_to = '$to' AND u_from = '$from' OR u_to = '$from' AND u_from = '$to';"));

                    if ($r > 0) {
                        $query = "SELECT DISTINCT chat_id FROM messages WHERE u_to = '$to' AND u_from = '$from' OR u_to = '$from' AND u_from = '$to';";
                        $result = mysqli_fetch_array(mysqli_query($conn, $query));
                        $chat_id = $result['chat_id'];
                        $query = "INSERT INTO messages (chat_id, u_to, u_from, message, time_sent, pic_url) VALUES ('$chat_id', '$to', '$from', '$msg', '$date', '$fileDestination');";
                    }
                    else {
                        $chat_id = md5($to.''.$from);
                        $query = "INSERT INTO messages (chat_id, u_to, u_from, message, time_sent, pic_url) VALUES ('$chat_id', '$to', '$from', '$msg', '$date', '$fileDestination');";
                    }

                    if (!mysqli_query($conn, $query)) {
                        echo 'Cannot send your message.';
                    }
                }
            }
        }
    }
?>