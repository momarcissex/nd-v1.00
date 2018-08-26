<?php

    session_start();
    include '../../dbh.php';
    require_once('../../vendor/autoload.php');
    date_default_timezone_set("UTC");
    $date = date("Y-m-d H:i:s", time());
    \Stripe\Stripe::setApiKey("sk_test_zFMuyMBC60raTKDdLGzR4wdb");

    $stripe_id = $_SESSION['stripe_acc'];
    $token = $_POST['token'];
    if($acct = \Stripe\Account::retrieve($stripe_id)) {
        $query = "SELECT * FROM thebag WHERE stripe_id = '$stripe_id';";
        $result = $conn->query($query);
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if($card = $acct->external_accounts->create(array("external_account" => $token, "default_for_currency" => true))) {
                $cardID = $card->id;
                $acct->external_accounts->retrieve($row['in_token'])->delete();
                $query = "UPDATE thebag SET in_token = '$cardID', i_date = '$date' WHERE stripe_id = '$stripe_id'";
                if (!$conn->query($query)) {
                    echo 'Could not update your payout method. Try later or contact support@nxtdrop.com'; 
                 }
            }
            else {
                echo 'Could not update your payout method. Try later.';
            }
        }
        else {
            $uid = $_SESSION['uid'];
            $query = "INSERT INTO thebag (uid, stripe_id, in_token, i_date) VALUES ('$uid', '$stripe_id', '$token', '$date')";
            if($card = $acct->external_accounts->create(array("external_account" => $token))) {
                $cardID = $card->id;
                $query = "INSERT INTO thebag (uid, stripe_id, in_token, i_date) VALUES ('$uid', '$stripe_id', '$cardID', '$date')";
                if (!$conn->query($query)) {
                    echo 'Could not update your payout method. Try later or contact support@nxtdrop.com'; 
                 }
            }
            else {
                echo 'Could not update your payout method. Try later.';
            }
        }
    }
    else {
        echo 'Error 102. Contact Support team @ support@nxtdrop.com';
    }

?>