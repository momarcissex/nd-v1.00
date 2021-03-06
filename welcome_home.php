<?php
    $retail = 22500;
    $price = 32600;
    $shipping = 1365;
    $consignment = $price*0.085;
    $legit_check = $price*0.03;
    $stripe_fees = (($price+$shipping)*0.029)+30;
    $net = $consignment - $stripe_fees;
    $seller_pay = $price - $consignment - $legit_check;
    $total = $price + $shipping;

    //echo 'Retail: '.$retail.'; Price: '.$price.'; Shipping: '.$shipping.'; Consignment: '.$consignment.'; Legit Check: '.$legit_check.'; Net: '.$net.'; Stripe Fees: '.$stripe_fees.'; Seller Pay: '.$seller_pay.'; Total: '.$total;
?>

<!DOCTYPE html>
<html>
    <title>
        NXTDROP: The Fashion Trade Centre
    </title>
    <head>
        <?php include('inc/head.php'); ?>
    </head>

    <body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-546WBVB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
        <script>
            $(document).ready(function() {
                var x;
                $('#search_index').focusin(function() {
                    $('.search_results_index').show();
                    $('#home-carousel').hide("slow");
                });

                $('#search_index').focusout(function() {
                    var search = $(this).val();

                    if (search != '') {
                        x = setTimeout(function() {
                            $('#home-carousel').show("slow");
                            $('.search_results_index').hide();
                        }, 300000);
                    }
                    else {
                        x = setTimeout(function() {
                            $('#home-carousel').show("slow");
                            $('.search_results_index').hide();
                        }, 2500);
                    }
                });

                $('#search_index').keyup(function() {
                    var search = $(this).val();

                    if (search != '') {
                        $('#home-carousel').hide("slow");
                    }

                    $.ajax({
                        url: 'inc/search_users.php',
                        type: 'GET',
                        data: {search: search},
                        dataType: 'text',
                        success: function(data) {
                            $('.search_results_index').html(data);
                        }
                    });
                });

                $(document).scroll(function() {
                    if ($(document).scrollTop() >= 60) {
                        $('.navbar').css('background', '#fff');
                        $('.navbar').css('border-bottom', '1px solid #e6e1e1');
                    }
                    else {
                        $('.navbar').css('background', 'transparent');
                        $('.navbar').css('border-bottom', 'none');
                    }
                });
            });
        </script>

        <nav class="navbar navbar-expand-xl nav_home">
            <a href="https://nxtdrop.com" id="navbar-brand"><img src="https://nxtdrop.com/img/nxtdroplogo.png"></a>
            <a id="login-btn" href="signin"><button class="sign-btn" title="Login/Sign Up" onClick="ga('send', 'event', 'button', 'click', 'signInBtn');">SIGN IN</button></a>
        </nav>

        <div id="home-carousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img class="d-block w-100" src="img/Nxtdrop101.png" alt="All shoes are verified authentic.">
                </div>
            </div>
        </div>

        <div class="search_bar_index">
            <input type="search" id="search_index" placeholder="What are you looking for?"/>
        </div>

        <div class="search_results_index">
            
        </div>

    </body>
</html>