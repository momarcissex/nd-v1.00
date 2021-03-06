<?php 
    session_start();
    include "dbh.php";
    include "inc/time.php";

    if (!isset($_SESSION['uid'])) {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html>
    <title>
        Inbox - NXTDROP - Canada's #1 Sneaker Marketplace
    </title>
    <head>
        <?php include('inc/head.php'); ?>
        <link rel="canonical" href="https://nxtdrop.com/inbox" />
        <!-- Javascripts -->
        <script type="text/javascript" src="js/menu-dropdown.js"></script>
        <script type="text/javascript" src="js/post-popup.js"></script>
        <script type="text/javascript" src="js/messages.js"></script>
        <script type="text/javascript" src="js/msg-popup.js"></script>
        <script type="text/javascript" src="js/dm_icon.js"></script>
    </head>

    <body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-546WBVB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
        <?php include('inc/navbar/navbar.php'); ?>
        <?php include('inc/inbox/message-body.php'); ?>
        <?php include('inc/inbox/new-msg.php'); ?>
        <?php include('inc/drop/new-drop-pop.php'); ?>
        <?php include('inc/search_pop.php') ?>
        <?php include('inc/inbox/image_preview.php'); ?>
        <?php include('inc/giveaway/popUp.php') ?>
    </body>
</html>