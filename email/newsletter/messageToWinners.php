<?php
    if(isset($_GET['email'])) {
        $email = $_GET['email'];
        $uname = $_GET['username'];
    }
    else {
        $email = '';
        $uname = '';
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <base href="https://nxtdrop.com/">
        <title>Black Friday Drop 🔥</title>
        <meta name="description" content="Welcome to NXTDROP">
        <meta name="author" content="NXTDROP, Inc.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Font-Awesome -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah|Roboto" rel="stylesheet">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>

        <style>
            * {
                padding: 0;
                margin: 0;
                list-style: none;
                -webkit-transition: background-color 0.5s ease-out;
                -moz-transition: background-color 0.5s ease-out;
                -o-transition: background-color 0.5s ease-out;
                transition: background-color 0.5s ease-out;
                -webkit-transition: border-color 0.5s ease-out;
                -moz-transition: border-color 0.5s ease-out;
                -o-transition: border-color 0.5s ease-out;
                transition: border-color 0.5s ease-out;
            }

            html {
                position: relative;
                min-height: 100%;
            }

            body {
                font-family: 'Roboto', sans-serif;
                background-color: #e6e6e6;
                height: 100%;
                background: #fff;
                max-width: 696px;
                margin: auto;   
            }

            .content {
                text-align: center;
                padding: 25px;
            }

            p {
                font-size: 20px;
                line-height: 30px;
            }

            h1 {
                margin: 10px auto;
                font-size: 24px;
            }

            #logo {
                width: 30%;
                margin: 10px 35%;
            }

            a:hover {
                color: #222222;
            }

            .footer {
                background: #bc3838;
                color: #fff;
                padding: 10px;
            }

            table {
                width: 90%;
                margin: 0px 5%;
            }

            a {
                text-decoration: none;
                color: #bc3838;
            }

            table a {
                letter-spacing: 2px;
                font-weight: 500;
                color: #fff;
            }

            table a:hover {
                color: #222222;
            }
        </style>
    </head>

    <body>
        <img src="https://nxtdrop.com/img/nxtdroplogo.png" alt="NXTDROP, Inc. Logo" id="logo">

        <div class="content">
            <h1>Dear <?php echo $uname; ?>,</h1>
            <p>We are informing you that due to the location of the seller, your order may take longer to get delivered. We apologize for the delay. If you already received your tracking #, please disregard this email.   You will get an update as soon as we receive your shoes for authentication.Thank you for your patience.</p>

            <p>The NXTDROP TEAM<br>
            <a href="mailto:support@nxtdrop.com">support@nxtdrop.com</a></p>
        </div>

        <div class="footer">
                <table style="margin: 0 0 10px 0;">
                    <tr style="font-size: 0.5rem;">
                        <th><a href="https://instagram.com/nxtdrop">INSTAGRAM</a></th>
                        <th><a href="https://twitter.com/nxtdrop">TWITTER</a></th>
                        <th><a href="https://nxtdrop.com/privacy">PRIVACY</a></th>
                        <th><a href="https://nxtdrop.com/terms">TERMS</a></th>
                    </tr>
                </table>
                <p style="font-size: 0.55rem; margin: 2.5px auto; width: 90%; text-align: center;">&copy; NXTDROP, Inc. All rights reserved.</p>
                <p style="font-size: 0.55rem; margin: 2.5px auto; width: 90%; text-align: center;">If you prefer not to receive emails like this from NXTDROP, you may <a href="<?php echo 'https://nxtdrop.com/unsubscribe/'.$email; ?>" style="text-decoration: underline; color: #424242;">unsubscribe</a></p>
            </div>
    </body>
</html>