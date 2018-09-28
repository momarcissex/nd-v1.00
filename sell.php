<?php 
    session_start();
    include "dbh.php";
?>
<!DOCTYPE html>
<html>
    <title>
        NXTDROP: The Fashion Trade Centre
    </title>
    <head>
        <?php include('inc/head.php'); ?>
        <!-- Javasripts -->
        <script type="text/javascript" src="js/delete-post.js"></script>
        <script type="text/javascript" src="js/like-unlike-post.js"></script>
        <script type="text/javascript">
            var ID;
            $(document).ready(function() {
                $('.sell_now-btn').hover(function() {
                    $(this).css('background-color', '#c64d53');
                    $(this).css('border-color', '#c64d53');
                }, function() {
                    $(this).css('background-color', '#f27178');
                    $(this).css('border-color', '#f27178');
                });

                $("#item_price-input").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl/cmd+A
                        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: Ctrl/cmd+C
                        (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: Ctrl/cmd+X
                        (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                            // let it happen, don't do anything
                            return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });

                $('#search_item').keyup(function(e) {
                    var search = $(this).val();
                    if(!isBlank(search) && !isEmpty(search)) {
                        $('.search_item-results').css('display', 'block');
                        if(e.keyCode != 32) {
                            $.ajax({
                                url: 'inc/sell/getProducts.php',
                                type: 'POST',
                                data: {search: search},
                                success: function(response) {
                                    if(response === 'CONNECTION') {
                                        console.log(response);
                                        alert('Log in or Sign Up');
                                    } else if(response === 'NO TEXT') {
                                        console.log(response);
                                        alert('Enter item you are looking to sell.');
                                    } else if(response === 'DB') {
                                        alert('Network problems. Sorry, try later.');
                                    }else {
                                        $('.search_item-results').html(response);
                                        if(isBlank($('#search_item').val()) && isEmpty($('#search_item').val())) {
                                            $('.search_item-results').css('display', 'none');
                                        } else if(isBlank(response) && isEmpty(response)) {
                                            $('.search_item-results').css('height', '20px');
                                        } else if(!isBlank(response) && !isEmpty(response)) {
                                            $('.search_item-results').css('height', '250px');
                                        }
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                    alert('We encountered an issue. Please, try later.');
                                }
                            });
                        }
                    } else {
                        $('.search_item-results').css('display', 'none');
                    }
                });

                $(".sell_now-btn").click(function() {
                    var condition = $('input[name="item_condition"]:checked').val();
                    var productID = ID;
                    var price = $('#item_price-input').val();
                    var size = $('#item_size').val();
                    //alert('Condition: ' + condition + '\n ProductID: ' + productID + '\n Price: ' + price + '\n Size: ' + size);
                    if(condition != 'new' && condition != 'used') {
                        alert('Select new or used to continue.');
                    } else if(typeof productID != 'number' || productID <= 0) {
                        alert('Please select an item to sell.');
                    } else if(price <= 0) {
                        alert('Please enter a price.');
                    } else if(size < 4 || size > 17) {
                        alert('Size selected is incorrect');
                    } else {
                        $(".sell_now-btn").html('<i class="fas fa-circle-notch fa-spin"></i>');
                        $.ajax({
                            url: 'inc/sell/list_item.php',
                            type: 'POST',
                            data: {condition: condition, productID: productID, price: price, size: size},
                            success: function(response) {
                                if(response === 'CONNECTION') {
                                    alert('Log in or Sign Up to sell.');
                                } else if(response === 'DB') {
                                    $('.sell_now-btn').html('Connection Error.');
                                    setTimeout(() => {
                                        $('.sell_now-btn').html('Sell Now');
                                    }, 7000);
                                } else if(response === 'GOOD') {
                                    $('.sell_now-btn').html('Listed').css('background', '#6fe2ac');
                                    $('#item_price-input').val('');
                                    $('#item_price-input').attr('placeholder', 'Enter Price');
                                    $('#item_size').val('0');
                                    $('#search_item').val('');
                                    $('input[name="item_condition"]').prop('checked', false);
                                    setTimeout(() => {
                                        $('.sell_now-btn').html('Sell Now').css('background', '#f27178');
                                    }, 7000);
                                } else if (response === 'MISSING') {
                                    $('.sell_now-btn').html('Blank field(s).');
                                    setTimeout(() => {
                                        $('.sell_now-btn').html('Sell Now');
                                    }, 7000);
                                } else {
                                    console.log(response);
                                    $('.sell_now-btn').html('Error. Try later.');
                                    setTimeout(() => {
                                        $('.sell_now-btn').html('Sell Now');
                                    }, 7000);
                                }
                            },
                            error: function() {
                                console.log(response);
                                alert('We encountered a problem. Please try later or contact support@nxtdrop.com');
                            }
                        });
                    }
                });
            });

            function isBlank(str) {
                return (!str || /^\s*$/.test(str));
            }

            function isEmpty(str) {
                return (!str || 0 === str.length);
            }

            function select_item(model, PID) {
                $('#search_item').val(model);
                ID = parseInt(PID);
                $('.search_item-results').css('display', 'none');
            }
        </script>
    </head>

    <body>
        <?php include('inc/navbar/navbar.php'); ?>

        <ol class="directions">
            <li>You&apos;ll be notified via email when a buyer purchases your item.</li>
            <li>Once you confirm the order, you&apos;ll have 48 hours to ship it and confirm that you shipped it.</li>
            <li>We&apos;ll verify the item and you&apos;ll get paid when it passes our verification process.</li>
            <li>If it doesn&apos;t pass our verification process, we&apos;ll charge you 15% of the item price. Please, don&apos;t make us take your money.</li>
            <li>We take a 12% consignment fee on all sales.</li>
        </ol>

        <div class="item_radios">
            <div class="form-check form-check-inline">
                <input name="item_condition" type="radio" id="new_item-radio" value="new" required>
                <label class="form-check-label" for="new_item-radio"> New</label>
            </div>
            <div class="form-check form-check-inline">
                <input name="item_condition" type="radio" id="used_item-radio" value="used" required>
                <label class="form-check-label" for="used_item-radio"> Used</label>
            </div>
        </div>

        <input type="search" id="search_item" placeholder="What do you want to sell?"/>
        <div class="search_item-results">
            
        </div>
        <small id="search_item_example">Example: Air Jordan 1 Chicago 2015</small><br>
        <small id="search_item_example">Click on item to select it.</small><br>

        <div class="input-group mb-3 item_price">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>
            <input id="item_price-input" type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="Enter Price">
            <div class="input-group-append">
                <span class="input-group-text">.00</span>
            </div>
        </div><br>

        <select name="size" id="item_size">
            <option value="0" selected>Select Size...</option>
            <option value="4">4</option>
            <option value="4.5">4.5</option>
            <option value="5">5</option>
            <option value="5.5">5.5</option>
            <option value="6">6</option>
            <option value="6.5">6.5</option>
            <option value="7">7</option>
            <option value="7.5">7.5</option>
            <option value="8">8</option>
            <option value="8.5">8.5</option>
            <option value="9">9</option>
            <option value="9.5">9.5</option>
            <option value="10">10</option>
            <option value="10.5">10.5</option>
            <option value="11">11</option>
            <option value="11.5">11.5</option>
            <option value="12">12</option>
            <option value="12.5">12.5</option>
            <option value="13">13</option>
            <option value="13.5">13.5</option>
            <option value="14">14</option>
            <option value="14.5">14.5</option>
            <option value="15">15</option>
            <option value="15.5">15.5</option>
            <option value="16">16</option>
            <option value="16.5">16.5</option>
            <option value="17">17</option>
        </select>

        <button class="sell_now-btn">Sell Now</button>

        <?php include('inc/talk/popup.php') ?>
        <?php //include('inc/drop/new-drop-pop.php'); ?>
        <?php include('inc/new-msg-post.php'); ?>
        <?php include('inc/flag-post.php'); ?>
        <?php include('inc/invite/popup.php'); ?>
        <?php include('inc/sold_pop.php') ?>
        <?php include('inc/search_pop.php') ?>
        <?php include('inc/buyer_transaction_confirmation.php') ?>
        <?php include('inc/notificationPopUp/sellerConfirmation.php') ?>
        <?php include('inc/notificationPopUp/MM_verification.php') ?>
        <?php //include('inc/giveaway/popUp.php') ?>

        <p id="message"></p>

    </body>
</html>