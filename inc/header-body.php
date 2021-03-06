<header>
    <div class="container-header">
        <a href="home"><i class="fa fa-home" aria-hidden="true" title="Home"></i></a>
        <style>
        .fa-stack[data-count]:after{
            position:absolute;
            right:5%;
            top:25%;
            content: attr(data-count);
            font-size:30%;
            padding:.6em;
            border-radius:999px;
            line-height:.75em;
            color: white;
            background:rgba(255,0,0,.85);
            text-align:center;
            min-width:0.5em;
            font-weight:bold;
        }
        .fa-stack {
            bottom: 2.25px;
        }
        </style>
        <?php
            if(isset($_SESSION['uid'])) {
                $uid = $_SESSION['uid'];
                $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) FROM messages, users WHERE u_to = '$uid' AND opened = '0';"));
                if ($result['COUNT(*)'] > 0) {
                    $message_icon = '<span class="fa-stack has-badge" data-count="">
                    <i class="fa fa-envelope fa-stack-1x"></i>
                </span>';
                }
                else {
                    $message_icon = '<span><i class="fa fa-envelope" aria-hidden="true" title="Direct Messages"></i></span>';
                }
                echo '<a href="likes"><i class="fa fa-heart-o" aria-hidden="true" title="Liked Drop"></i></a>
                <a href="u/'.$_SESSION['username'].'"><i class="fa fa-user" aria-hidden="true" title="Profile"></i></a>
                <a href="inbox" class="dm_icon">'.$message_icon.'</a>';
            }
            else {
                echo '<a href="login_signup"><i class="fa fa-heart-o" aria-hidden="true" title="Liked Drop"></i></a>
                <a href="login_signup"><i class="fa fa-user" aria-hidden="true" title="Profile"></i></a>
                <a href="login_signup"><i class="fa fa-envelope" aria-hidden="true" title="Direct Messages"></i></a>';
            }
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#search').keyup(function() {
                    var name = $(this).val();
                    if (name != '') {
                        $.ajax({
                            type: 'POST',
                            url: 'inc/search_bar.php',
                            data: {name: name},
                            success: function(data) {
                                $('.search_result').fadeIn(100);
                                $('.search_result').html(data);
                            }
                        });
                    }
                    else {
                        $('.search_result').fadeOut();
                    }
                });

                $('#search').blur(function(){
                    $('.search_result').fadeOut(500);
                });

                $(document).on('click', 'li.user_r', function() {
                    $('#search').val($(this).text());
                });

                $('#search').keydown(function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        var q = $('#search').val();
                        window.location.replace("search/"+q);
                    }
                });
            });
        </script>
        <img id ="logo" src="img/nxtdroplogo.png">
        <?php
            if(isset($_SESSION['uid'])) {
                echo '<div class="search-bar">
                <form action="" method="GET" id="search-bar">
                    <input type="text" name="q" id="search" autocomplete="off" spellcheck="false" placeholder="Search" />
                </form>
                <div class="search_result"></div>
            </div>
                <button class="call_post">New Drop</button>
                <div class="dropdown"><i onclick="more()" class="fa fa-ellipsis-h" aria-hidden="true" id="dropbtn" title="Log Out"></i><div id="myDropdown" class="dropdown-content"><a href="logout">Log Out</a></div></div>';   
            }
            else {
                echo '<div class="search-bar">
                <form action="" method="GET" id="search-bar">
                    <input type="text" name="q" id="search" autocomplete="off" spellcheck="false" placeholder="Search" />
                </form>
                <div class="search_result"></div>
            </div>
                <a href="login_signup"><button class="login-button">Sign Up/Login</button></a>';
            }
        ?>
    </div>
</header>