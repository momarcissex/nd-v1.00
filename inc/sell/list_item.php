<?php

    session_start();
    require_once('../../dbh.php');
    $conn->autocommit(false);
    $listItem = $conn->prepare("INSERT INTO offers (productID, sellerID, price, size, productCondition, date) VALUES (?, ?, ?, ?, ?, ?);");
    $listItem->bind_param("iiiiss", $productID, $userID, $price, $size, $condition, $date);
    date_default_timezone_set("UTC");

    if(!isset($_SESSION['uid'])) {
        die('CONNECTION');
    } else {
        if(!isset($_POST['productID']) && !isset($_POST['price']) && !isset($_POST['size']) && !isset($_POST['condition'])) {
            die('MISSING');
        } else {
            $price = $_POST['price'];
            $size = $_POST['size'];
            $condition = $_POST['condition'];
            $productID = $_POST['productID'];
            $userID = $_SESSION['uid'];
            $date = date("Y-m-d H:i:s", time());
            if($listItem->execute()) {
                $conn->commit();
                die('GOOD');
            } else {
                $conn->rollback();
                die('DB');
            }
        }
    }

?>