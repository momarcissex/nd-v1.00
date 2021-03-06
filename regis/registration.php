<?php

    include '../dbh.php';
    require_once('../../credentials.php');
    require_once('../vendor/autoload.php');
    include('../email/Email.php');
    \Stripe\Stripe::setApiKey($STRIPE_TEST_SECRET_KEY);
    date_default_timezone_set("UTC"); 
    $date = date("Y-m-d H:i:s", time());

    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $name = $conn->real_escape_string($_POST['name']);
    $uName = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $pwd = $conn->real_escape_string($_POST['pwd']);
    $country = $conn->real_escape_string($_POST['country']);
    $inviteCode = $conn->real_escape_string($_POST['invite_code']);
    $iCodeError = false;
    $errorEmail = false;
    $errorUsername = false;

    if(isset($_POST['submit'])) {
        if (empty($uName) || empty($email) || empty($pwd)) {
            echo "Fill in all the fields!";
            $errorEmpty = true;
        }
        
        $sql = "SELECT email FROM users WHERE email = '$email';";
        $result = $conn->query($sql);
        $check = mysqli_num_rows($result);
        if($check > 0) {
            echo "E-mail already used!";
            $errorEmail = true;
        }

        $sql = "SELECT username FROM users WHERE username = '$uName';";
        $result = $conn->query($sql);
        $check = mysqli_num_rows($result);
        if ($check > 0) {
            echo "Username already used!";
            $errorUsername = true;
        }

        $result = $conn->query("SELECT * FROM users_code, invitationUsage WHERE users_code.invite_code = '$inviteCode' AND invitationUsage.codeID != users_code.codeID");
        $check = mysqli_num_rows($result);
        if($check > 0) {
            echo 'Invite code invalid or already used';
            $iCodeError = true;
        }

        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'INVALID EMAIL';
            $errorEmail = true;
        }

        if($iCodeError == false && $errorEmail == false && $errorUsername == false) {
            $pwd = md5($pwd);
            $conn->autocommit(false);
            $createUser = $conn->query("INSERT INTO users (name, username, email, pwd, account_created, country, active_account) VALUES ('$name', '$uName', '$email', '$pwd', '$date', '$country', '0')");
            $getNewUser = $conn->query("SELECT * FROM users WHERE username = '$uName'");
            if ($createUser && $getNewUser) {

                $row = $getNewUser->fetch_assoc();
                $uid = $row['uid'];
                $createProfile = $conn->query("INSERT INTO profile (uid) VALUES ('$uid')");

                $code = mysqli_fetch_assoc($result);
                $codeID = $code['codeID'];
                if($check > 0) {$showInvite = $conn->query("INSERT INTO invitationUsage (usedBy, codeID, dateUsed) VALUES ('$uid', '$codeID', '$date')");}
                else {$showInvite = true;}

                $iCode = generateRandomString(6);
                $iCodeDate = date("Y-m-d H:i:s", time());
                $insertICode = true; //= $conn->query("INSERT INTO users_code (uid, invite_code, dateGenerated) VALUES ('$uid', '$iCode', '$iCodeDate')");
                
                if($createProfile && $insertICode && $showInvite) {
                    $thebag = $conn->query("INSERT INTO thebag (uid) VALUES ('$uid')");
                    if($thebag) {
                        $conn->commit();
                        session_start();
                        $_SESSION['uid'] = $uid;
                        $_SESSION['name'] = $name;
                        $_SESSION['username'] = $uName;
                        $_SESSION['email'] = $email;
                        $_SESSION['country'] = $country;

                        $createEmail = new Email($name, $email, 'hello@nxtdrop.com', 'Hi '.$uName.', welcome to NXTDROP', '');
                        if(!$createEmail->sendEmail('registration')) {
                            echo '';
                        }
                    }
                    else {
                        $email = new \SendGrid\Mail\Mail(); 
                        $email->setFrom("admin@nxtdrop.com", "NXTDROP ERROR");
                        $email->setSubject("URGENT! Error Registration Stripe.");
                        $email->addTo('momar@nxtdrop.com', 'MOMAR CISSE');
                        $html = "<p>Username: " . $uName . ", stripe_id: " . $account_id . ", customer_id: " . $cus_id . ", Date: " . date("Y-m-d H:i:s", time()) . ", Message: Couldn't update Stripe accounts IDs to Database. Please do so manually. Thank You!</p>";
                        $email->addContent("text/html", $html);
                        $sendgrid = new \SendGrid($SD_TEST_API_KEY);
                        try {
                            $sendgrid->send($email);
                        } catch (Exception $e) {
                            $cu = \Stripe\Customer::retrieve($cus_id);
                            $ac = \Stripe\Account::retrieve($account_id);
                            $cu->delete();
                            $ac->delete();
                            $conn->rollback();
                            die('DB');
                        }  
                        $conn->commit();          
                    }
                }
                else {
                    $email = new \SendGrid\Mail\Mail(); 
                    $email->setFrom("admin@nxtdrop.com", "NXTDROP");
                    $email->setSubject("URGENT! Error Update User Regis.");
                    $email->addTo('momar@nxtdrop.com', 'MOMAR CISSE');
                    $html = "<p>Cannot create profile, insert Code or show invite.</p>";
                    $email->addContent("text/html", $html);
                    $sendgrid = new \SendGrid($SD_TEST_API_KEY);
                    try {
                        $response = $sendgrid->send($email);
                    } catch (Exception $e) {
                        die('DB');
                    }
                    $conn->rollback();
                    echo 'DB';
                }
            }
            else {
                $conn->rollback();
                echo 'DB';
            }
        }
    }
    else {
        echo "There was an error!";
    }

    function errorLog($e) {
        $log_filename = $_SERVER['DOCUMENT_ROOT']."/log";

        $body = $e->getJsonBody();
        $err  = $body['error'];
        $log_msg = 'Status is:' . $e->getHttpStatus() . "\n" . 'Type is:' . $err['type'] . "\n" . 'Message is:' . $err['message'] . "\n" . 'Date:' . date("Y-m-d H:i:s", time());

        if (!file_exists($log_filename))
        {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
        file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
    }
?>